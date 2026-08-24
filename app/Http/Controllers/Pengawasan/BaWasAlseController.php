<?php

namespace App\Http\Controllers\Pengawasan;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\BaWasAlse;
use App\Models\PelakuUsaha;
use App\Models\Provinsi;
use App\Http\Requests\BaWasAlseRequest;
use App\Traits\ResolvesPelakuUsaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class BaWasAlseController extends Controller
{
    use ResolvesPelakuUsaha;

    public function index()
    {
        $pelakuUsahas = PelakuUsaha::orderBy('nama_perusahaan')->get();
        return view('ba-was-alse.index', compact('pelakuUsahas'));
    }

    public function data(Request $request)
    {
        $query = BaWasAlse::with('pelakuUsaha')->filter($request->all());

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('perusahaan', fn ($r) => $r->pelakuUsaha->nama_perusahaan ?? $r->nama_usaha ?? '-')
            ->addColumn('tanggal', fn ($r) => $r->tanggal_pengawasan?->format('d/m/Y'))
            ->addColumn('status_badge', fn ($r) => '<span class="badge bg-'.match ($r->status) {
                'selesai' => 'success', 'proses' => 'warning', 'tindak_lanjut' => 'danger', default => 'secondary',
            }.'">'.ucwords(str_replace('_', ' ', $r->status)).'</span>')
            ->addColumn('aksi', fn ($r) => view('ba-was-alse.partials.aksi', ['row' => $r])->render())
            ->rawColumns(['status_badge', 'aksi'])
            ->make(true);
    }

    public function create()
    {
        $pelakuUsahas = PelakuUsaha::orderBy('nama_perusahaan')->get();
        $provinsis = Provinsi::orderBy('nama')->get();
        return view('ba-was-alse.create', compact('pelakuUsahas', 'provinsis'));
    }

    public function store(BaWasAlseRequest $request)
    {
        $data = $request->validated();
        $data['pelaku_usaha_id'] = $this->resolvePelakuUsahaId($data['pelaku_usaha_id'] ?? null, $data['provinsi_id'] ?? null, $data['alamat_kantor'] ?? $data['alamat_kegiatan'] ?? null);
        unset($data['file_ba_pdf'], $data['foto'], $data['pengawas'], $data['saksi']);

        if ($request->hasFile('file_ba_pdf')) {
            $data['file_ba_pdf'] = $request->file('file_ba_pdf')->store('ba-was-alse', 'public');
        }

        $data['ketua_tim_tanda_tangan'] = $this->simpanTandaTangan($data['ketua_tim_tanda_tangan'] ?? null);
        $data['pj_usaha_tanda_tangan'] = $this->simpanTandaTangan($data['pj_usaha_tanda_tangan'] ?? null);

        $data['created_by'] = auth()->id();
        $ba = BaWasAlse::create($data);

        // Simpan foto dokumentasi
        foreach ($request->file('foto', []) as $foto) {
            $ba->fotos()->create(['path_foto' => $foto->store('ba-was-alse/foto', 'public')]);
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

        ActivityLog::catat('Tambah', 'BA WAS ALSE', "Menambahkan BA WAS ALSE: {$ba->nomor_ba}");

        return redirect()->route('ba-was-alse.index')->with('success', 'BA WAS ALSE berhasil ditambahkan.');
    }

    public function show(BaWasAlse $baWasAlse)
    {
        $baWasAlse->load(['pelakuUsaha', 'fotos', 'pengawas', 'saksis', 'provinsi']);
        return view('ba-was-alse.show', compact('baWasAlse'));
    }

    public function edit(BaWasAlse $baWasAlse)
    {
        $baWasAlse->load(['pengawas', 'saksis']);
        $pelakuUsahas = PelakuUsaha::orderBy('nama_perusahaan')->get();
        $provinsis = Provinsi::orderBy('nama')->get();
        return view('ba-was-alse.edit', compact('baWasAlse', 'pelakuUsahas', 'provinsis'));
    }

    public function update(BaWasAlseRequest $request, BaWasAlse $baWasAlse)
    {
        $data = $request->validated();
        $oldPelakuUsahaId = $baWasAlse->pelaku_usaha_id;
        $data['pelaku_usaha_id'] = $this->resolvePelakuUsahaId($data['pelaku_usaha_id'] ?? null, $data['provinsi_id'] ?? null, $data['alamat_kantor'] ?? $data['alamat_kegiatan'] ?? null);
        unset($data['file_ba_pdf'], $data['foto'], $data['pengawas'], $data['saksi']);

        if ($request->hasFile('file_ba_pdf')) {
            $data['file_ba_pdf'] = $request->file('file_ba_pdf')->store('ba-was-alse', 'public');
        }

        $data['ketua_tim_tanda_tangan'] = $this->simpanTandaTangan($data['ketua_tim_tanda_tangan'] ?? null);
        $data['pj_usaha_tanda_tangan'] = $this->simpanTandaTangan($data['pj_usaha_tanda_tangan'] ?? null);

        $baWasAlse->update($data);

        // Bersihkan PelakuUsaha lama jika sudah tidak dirujuk BA manapun
        $this->cleanupOrphanedPelakuUsaha($oldPelakuUsahaId, $data['pelaku_usaha_id']);

        // Simpan foto dokumentasi baru
        foreach ($request->file('foto', []) as $foto) {
            $baWasAlse->fotos()->create(['path_foto' => $foto->store('ba-was-alse/foto', 'public')]);
        }

        // Sync anggota pengawas (hapus lama, buat ulang)
        $baWasAlse->pengawas()->delete();
        foreach ($request->input('pengawas', []) as $p) {
            if (!empty($p['nama'])) {
                $p['tanda_tangan'] = $this->simpanTandaTangan($p['tanda_tangan'] ?? null);
                $baWasAlse->pengawas()->create($p);
            }
        }

        // Sync saksi (hapus lama, buat ulang)
        $baWasAlse->saksis()->delete();
        foreach ($request->input('saksi', []) as $s) {
            if (!empty($s['nama'])) {
                $s['tanda_tangan'] = $this->simpanTandaTangan($s['tanda_tangan'] ?? null);
                $baWasAlse->saksis()->create($s);
            }
        }

        ActivityLog::catat('Edit', 'BA WAS ALSE', "Mengubah BA WAS ALSE: {$baWasAlse->nomor_ba}");

        return redirect()->route('ba-was-alse.index')->with('success', 'BA WAS ALSE berhasil diperbarui.');
    }

    public function destroy(BaWasAlse $baWasAlse)
    {
        ActivityLog::catat('Hapus', 'BA WAS ALSE', "Menghapus BA WAS ALSE: {$baWasAlse->nomor_ba}");
        $baWasAlse->delete();

        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus.']);
    }

    public function cetak(BaWasAlse $baWasAlse)
    {
        $baWasAlse->load(['pelakuUsaha.provinsi', 'fotos', 'pengawas', 'saksis', 'provinsi']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('ba-was-alse.cetak', compact('baWasAlse'))
            ->setPaper('a4', 'portrait')
            ->setOption('isRemoteEnabled', true);

        return $pdf->stream($this->namaFileCetak($baWasAlse->nomor_ba));
    }

    private function namaFileCetak(?string $nomorBa): string
    {
        $nama = 'BA-WAS-ALSE-' . ($nomorBa ?: 'draft');
        $nama = preg_replace('/[\/\\\\:*?"<>|]+/', '-', $nama);
        $nama = preg_replace('/-+/', '-', $nama);

        return trim($nama, '-') . '.pdf';
    }



    private function simpanTandaTangan(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        if (!str_starts_with($value, 'data:image/')) {
            return $value;
        }

        if (!preg_match('/^data:image\/(png|jpeg);base64,(.+)$/', $value, $matches)) {
            return null;
        }

        $binary = base64_decode($matches[2]);

        if ($binary === false || strlen($binary) < 100) {
            return null;
        }

        $ext = $matches[1] === 'jpeg' ? 'jpg' : 'png';
        $filename = 'ba-was-alse/ttd/' . Str::uuid() . '.' . $ext;
        Storage::disk('public')->put($filename, $binary);

        return $filename;
    }
}
