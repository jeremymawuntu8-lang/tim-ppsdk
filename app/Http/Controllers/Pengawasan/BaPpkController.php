<?php

namespace App\Http\Controllers\Pengawasan;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\BaPpk;
use App\Models\PelakuUsaha;
use App\Http\Requests\BaPpkRequest;
use App\Traits\ResolvesPelakuUsaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class BaPpkController extends Controller
{
    use ResolvesPelakuUsaha;

    public function index()
    {
        $pelakuUsahas = PelakuUsaha::orderBy('nama_perusahaan')->get();
        return view('ba-ppk.index', compact('pelakuUsahas'));
    }

    public function data(Request $request)
    {
        $query = BaPpk::with('pelakuUsaha')->filter($request->all());

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('perusahaan', fn ($r) => $r->pelakuUsaha->nama_perusahaan ?? $r->nama_pj ?? '-')
            ->addColumn('tanggal', fn ($r) => $r->tanggal_pengawasan?->format('d/m/Y'))
            ->addColumn('status_badge', fn ($r) => '<span class="badge bg-'.match ($r->status) {
                'selesai' => 'success', 'proses' => 'warning', 'tindak_lanjut' => 'danger', default => 'secondary',
            }.'">'.ucwords(str_replace('_', ' ', $r->status)).'</span>')
            ->addColumn('aksi', fn ($r) => view('ba-ppk.partials.aksi', ['row' => $r])->render())
            ->rawColumns(['status_badge', 'aksi'])
            ->make(true);
    }

    public function create()
    {
        $pelakuUsahas = PelakuUsaha::orderBy('nama_perusahaan')->get();
        return view('ba-ppk.create', compact('pelakuUsahas'));
    }

    public function store(BaPpkRequest $request)
    {
        $data = $request->validated();
        $data['pelaku_usaha_id'] = $this->resolvePelakuUsahaId($data['pelaku_usaha_id'] ?? null, null, $data['alamat_pj'] ?? null);
        
        $this->sanitizeBooleans($data);
        unset($data['foto'], $data['pengawas']);

        $data['ttd_pelaku_usaha'] = $this->simpanTandaTangan($data['ttd_pelaku_usaha'] ?? null);
        $data['ttd_pengawas_1'] = $this->simpanTandaTangan($data['ttd_pengawas_1'] ?? null);

        $data['created_by'] = auth()->id();
        $ba = BaPpk::create($data);

        foreach ($request->file('foto', []) as $foto) {
            $ba->fotos()->create(['path_foto' => $foto->store('ba-ppk/foto', 'public')]);
        }

        foreach ($request->input('pengawas', []) as $p) {
            if (!empty($p['nama'])) {
                $ba->pengawas()->create($p);
            }
        }

        ActivityLog::catat('Tambah', 'BA PPK', "Menambahkan BA PPK: {$ba->nomor_ba}");

        return redirect()->route('ba-ppk.index')->with('success', 'BA PPK berhasil ditambahkan.');
    }

    public function show(BaPpk $baPpk)
    {
        $baPpk->load(['pelakuUsaha', 'fotos', 'pengawas']);
        return view('ba-ppk.show', compact('baPpk'));
    }

    public function edit(BaPpk $baPpk)
    {
        $baPpk->load(['pengawas']);
        $pelakuUsahas = PelakuUsaha::orderBy('nama_perusahaan')->get();
        return view('ba-ppk.edit', compact('baPpk', 'pelakuUsahas'));
    }

    public function update(BaPpkRequest $request, BaPpk $baPpk)
    {
        $data = $request->validated();
        $oldPelakuUsahaId = $baPpk->pelaku_usaha_id;
        $data['pelaku_usaha_id'] = $this->resolvePelakuUsahaId($data['pelaku_usaha_id'] ?? null, null, $data['alamat_pj'] ?? null);
        
        $this->sanitizeBooleans($data);
        unset($data['foto'], $data['pengawas']);

        $data['ttd_pelaku_usaha'] = $this->simpanTandaTangan($data['ttd_pelaku_usaha'] ?? null);
        $data['ttd_pengawas_1'] = $this->simpanTandaTangan($data['ttd_pengawas_1'] ?? null);

        $baPpk->update($data);

        // Bersihkan PelakuUsaha lama jika sudah tidak dirujuk BA manapun
        $this->cleanupOrphanedPelakuUsaha($oldPelakuUsahaId, $data['pelaku_usaha_id']);

        foreach ($request->file('foto', []) as $foto) {
            $baPpk->fotos()->create(['path_foto' => $foto->store('ba-ppk/foto', 'public')]);
        }

        $baPpk->pengawas()->delete();
        foreach ($request->input('pengawas', []) as $p) {
            if (!empty($p['nama'])) {
                $baPpk->pengawas()->create($p);
            }
        }

        ActivityLog::catat('Edit', 'BA PPK', "Mengubah BA PPK: {$baPpk->nomor_ba}");

        return redirect()->route('ba-ppk.index')->with('success', 'BA PPK berhasil diperbarui.');
    }

    public function destroy(BaPpk $baPpk)
    {
        ActivityLog::catat('Hapus', 'BA PPK', "Menghapus BA PPK: {$baPpk->nomor_ba}");
        $baPpk->delete();
        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus.']);
    }

    public function cetak(BaPpk $baPpk)
    {
        $baPpk->load(['pelakuUsaha', 'fotos', 'pengawas']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('ba-ppk.cetak', compact('baPpk'))
            ->setPaper('a4', 'portrait')
            ->setOption('isRemoteEnabled', true);

        return $pdf->stream($this->namaFileCetak($baPpk->nomor_ba));
    }

    private function namaFileCetak(?string $nomorBa): string
    {
        $nama = 'BA-PPK-' . ($nomorBa ?: 'draft');
        $nama = preg_replace('/[\/\\\\:*?"<>|]+/', '-', $nama);
        $nama = preg_replace('/-+/', '-', $nama);
        return trim($nama, '-') . '.pdf';
    }



    private function sanitizeBooleans(array &$data): void
    {
        $boolFields = [
            'syarat_rdtr_belum', 'syarat_rdtr_non_oss', 'syarat_rtr_zonasi', 'syarat_pengecualian_pkkpr',
            'rek_ppk_ada', 'pkkpr_ada', 'lingkungan_ada', 'nib_ada', 'izin_usaha_ada', 'dok_lain_ada',
            'dugaan_pelanggaran_ada', 'dugaan_kerusakan_ada'
        ];
        foreach ($boolFields as $field) {
            $data[$field] = isset($data[$field]) ? (bool)$data[$field] : false;
        }
    }

    private function simpanTandaTangan(?string $value): ?string
    {
        if (empty($value)) return null;
        if (!str_starts_with($value, 'data:image/')) return $value;
        if (!preg_match('/^data:image\/(png|jpeg);base64,(.+)$/', $value, $matches)) return null;
        
        $binary = base64_decode($matches[2]);
        if ($binary === false || strlen($binary) < 100) return null;

        $ext = $matches[1] === 'jpeg' ? 'jpg' : 'png';
        $filename = 'ba-ppk/ttd/' . Str::uuid() . '.' . $ext;
        Storage::disk('public')->put($filename, $binary);

        return $filename;
    }
}
