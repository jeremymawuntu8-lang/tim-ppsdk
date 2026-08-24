<?php

namespace App\Http\Controllers\Pengawasan;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\BaWasPrl;
use App\Models\PelakuUsaha;
use App\Models\Provinsi;
use App\Http\Requests\BaWasPrlRequest;
use App\Traits\ResolvesPelakuUsaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class BaWasPrlController extends Controller
{
    use ResolvesPelakuUsaha;

    public function index()
    {
        $pelakuUsahas = PelakuUsaha::orderBy('nama_perusahaan')->get();
        return view('ba-was-prl.index', compact('pelakuUsahas'));
    }

    public function data(Request $request)
    {
        $query = BaWasPrl::with('pelakuUsaha')->filter($request->all());

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('perusahaan', fn ($r) => $r->pelakuUsaha->nama_perusahaan ?? '-')
            ->addColumn('tanggal', fn ($r) => $r->tanggal_pengawasan?->format('d/m/Y'))
            ->addColumn('status_badge', fn ($r) => '<span class="badge bg-'.match ($r->status) {
                'selesai' => 'success', 'proses' => 'warning', 'tindak_lanjut' => 'danger', default => 'secondary',
            }.'">'.ucwords(str_replace('_', ' ', $r->status)).'</span>')
            ->addColumn('aksi', fn ($r) => view('ba-was-prl.partials.aksi', ['row' => $r])->render())
            ->rawColumns(['status_badge', 'aksi'])
            ->make(true);
    }

    public function create()
    {
        $pelakuUsahas = PelakuUsaha::orderBy('nama_perusahaan')->get();
        $provinsis = Provinsi::orderBy('nama')->get();
        return view('ba-was-prl.create', compact('pelakuUsahas', 'provinsis'));
    }

    public function store(BaWasPrlRequest $request)
    {
        $data = $request->validated();
        $data['pelaku_usaha_id'] = $this->resolvePelakuUsahaId($data['pelaku_usaha_id'] ?? null, $data['provinsi_id'] ?? null, $data['lokasi'] ?? null);
        unset($data['file_ba_pdf'], $data['foto'], $data['pengawas'], $data['saksi']);

        if ($request->hasFile('file_ba_pdf')) {
            $data['file_ba_pdf'] = $request->file('file_ba_pdf')->store('ba-was-prl', 'public');
        }

        $data['ketua_tim_tanda_tangan'] = $this->simpanTandaTangan($data['ketua_tim_tanda_tangan'] ?? null);
        $data['pj_usaha_tanda_tangan'] = $this->simpanTandaTangan($data['pj_usaha_tanda_tangan'] ?? null);

        $data['created_by'] = auth()->id();
        $ba = BaWasPrl::create($data);

        // Simpan foto dokumentasi
        foreach ($request->file('foto', []) as $foto) {
            $ba->fotos()->create(['path_foto' => $foto->store('ba-was-prl/foto', 'public')]);
        }

        // Simpan anggota pengawas
        foreach ($request->input('pengawas', []) as $p) {
            if (!empty($p['nama'])) {
                $p['tanda_tangan'] = $this->simpanTandaTangan($p['tanda_tangan'] ?? null);
                $ba->pengawas()->create($p);
            }
        }

        // Simpan saksi
        foreach ($request->input('saksi', []) as $s) {
            if (!empty($s['nama'])) {
                $s['tanda_tangan'] = $this->simpanTandaTangan($s['tanda_tangan'] ?? null);
                $ba->saksis()->create($s);
            }
        }

        ActivityLog::catat('Tambah', 'BA WAS PRL', "Menambahkan BA WAS PRL: {$ba->nomor_ba}");

        return redirect()->route('ba-was-prl.index')->with('success', 'BA WAS PRL berhasil ditambahkan.');
    }

    public function show(BaWasPrl $baWasPrl)
    {
        $baWasPrl->load(['pelakuUsaha', 'fotos', 'pengawas', 'saksis']);
        return view('ba-was-prl.show', compact('baWasPrl'));
    }

    public function edit(BaWasPrl $baWasPrl)
    {
        $this->authorize('update', $baWasPrl);

        $baWasPrl->load(['pengawas', 'saksis']);
        $pelakuUsahas = PelakuUsaha::orderBy('nama_perusahaan')->get();
        $provinsis = Provinsi::orderBy('nama')->get();
        return view('ba-was-prl.edit', compact('baWasPrl', 'pelakuUsahas', 'provinsis'));
    }

    public function update(BaWasPrlRequest $request, BaWasPrl $baWasPrl)
    {
        $this->authorize('update', $baWasPrl);

        $data = $request->validated();
        $oldPelakuUsahaId = $baWasPrl->pelaku_usaha_id;
        $data['pelaku_usaha_id'] = $this->resolvePelakuUsahaId($data['pelaku_usaha_id'] ?? null, $data['provinsi_id'] ?? null, $data['lokasi'] ?? null);
        unset($data['file_ba_pdf'], $data['foto'], $data['pengawas'], $data['saksi']);

        if ($request->hasFile('file_ba_pdf')) {
            $data['file_ba_pdf'] = $request->file('file_ba_pdf')->store('ba-was-prl', 'public');
        }

        $data['ketua_tim_tanda_tangan'] = $this->simpanTandaTangan($data['ketua_tim_tanda_tangan'] ?? null);
        $data['pj_usaha_tanda_tangan'] = $this->simpanTandaTangan($data['pj_usaha_tanda_tangan'] ?? null);

        $baWasPrl->update($data);

        // Bersihkan PelakuUsaha lama jika sudah tidak dirujuk BA manapun
        $this->cleanupOrphanedPelakuUsaha($oldPelakuUsahaId, $data['pelaku_usaha_id']);

        // Simpan foto dokumentasi baru
        foreach ($request->file('foto', []) as $foto) {
            $baWasPrl->fotos()->create(['path_foto' => $foto->store('ba-was-prl/foto', 'public')]);
        }

        // Sync anggota pengawas (hapus lama, buat ulang)
        $baWasPrl->pengawas()->delete();
        foreach ($request->input('pengawas', []) as $p) {
            if (!empty($p['nama'])) {
                $p['tanda_tangan'] = $this->simpanTandaTangan($p['tanda_tangan'] ?? null);
                $baWasPrl->pengawas()->create($p);
            }
        }

        // Sync saksi (hapus lama, buat ulang)
        $baWasPrl->saksis()->delete();
        foreach ($request->input('saksi', []) as $s) {
            if (!empty($s['nama'])) {
                $s['tanda_tangan'] = $this->simpanTandaTangan($s['tanda_tangan'] ?? null);
                $baWasPrl->saksis()->create($s);
            }
        }

        ActivityLog::catat('Edit', 'BA WAS PRL', "Mengubah BA WAS PRL: {$baWasPrl->nomor_ba}");

        return redirect()->route('ba-was-prl.index')->with('success', 'BA WAS PRL berhasil diperbarui.');
    }

    public function destroy(BaWasPrl $baWasPrl)
    {
        $this->authorize('delete', $baWasPrl);

        ActivityLog::catat('Hapus', 'BA WAS PRL', "Menghapus BA WAS PRL: {$baWasPrl->nomor_ba}");
        $baWasPrl->delete();

        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus.']);
    }

    public function cetak(BaWasPrl $baWasPrl)
    {
        $baWasPrl->load(['pelakuUsaha.jenisUsaha', 'pelakuUsaha.provinsi', 'fotos', 'pengawas', 'saksis', 'provinsi']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('ba-was-prl.cetak', compact('baWasPrl'))
            ->setPaper('a4', 'portrait')
            ->setOption('isRemoteEnabled', true);

        return $pdf->stream($this->namaFileCetak($baWasPrl->nomor_ba));
    }

    /**
     * nomor_ba diisi bebas oleh pengguna dan lazimnya mengandung karakter "/"
     * (format naskah dinas, mis. "B.2320/PSDKPLan.5/KP.440/VI/2026") yang TIDAK
     * boleh dipakai langsung sebagai nama file unduhan (menyebabkan error pada
     * header Content-Disposition). Karakter tidak aman diganti dengan "-".
     */
    private function namaFileCetak(?string $nomorBa): string
    {
        $nama = 'BA-WAS-PRL-' . ($nomorBa ?: 'draft');
        $nama = preg_replace('/[\/\\\\:*?"<>|]+/', '-', $nama);
        $nama = preg_replace('/-+/', '-', $nama);

        return trim($nama, '-') . '.pdf';
    }



    /**
     * Ambil hasil signature pad (data URI base64) lalu simpan sebagai file PNG/JPEG
     * di storage publik. Jika nilainya sudah berupa path file (tanda tangan lama
     * yang tidak diubah), kembalikan apa adanya. Jika kosong, kembalikan null
     * (berarti belum/tidak ada tanda tangan).
     */
    private function simpanTandaTangan(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        if (!str_starts_with($value, 'data:image/')) {
            // Sudah berupa path file tersimpan (tidak diubah saat edit), biarkan apa adanya.
            return $value;
        }

        if (!preg_match('/^data:image\/(png|jpeg);base64,(.+)$/', $value, $matches)) {
            return null;
        }

        $binary = base64_decode($matches[2]);

        if ($binary === false || strlen($binary) < 100) {
            // Kanvas kosong biasanya menghasilkan data yang sangat kecil / tidak valid.
            return null;
        }

        $ext = $matches[1] === 'jpeg' ? 'jpg' : 'png';
        $filename = 'ba-was-prl/ttd/' . Str::uuid() . '.' . $ext;
        Storage::disk('public')->put($filename, $binary);

        return $filename;
    }
}
