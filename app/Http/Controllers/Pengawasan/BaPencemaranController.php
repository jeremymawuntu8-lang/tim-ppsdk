<?php

namespace App\Http\Controllers\Pengawasan;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\BaPencemaran;
use App\Models\PelakuUsaha;
use App\Http\Requests\BaPencemaranRequest;
use App\Traits\ResolvesPelakuUsaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class BaPencemaranController extends Controller
{
    use ResolvesPelakuUsaha;

    public function index()
    {
        $pelakuUsahas = PelakuUsaha::orderBy('nama_perusahaan')->get();
        return view('ba-pencemaran.index', compact('pelakuUsahas'));
    }

    public function data(Request $request)
    {
        $query = BaPencemaran::with('pelakuUsaha')->filter($request->all());

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('perusahaan', fn ($r) => $r->pelakuUsaha->nama_perusahaan ?? $r->nama_pj ?? '-')
            ->addColumn('tanggal', fn ($r) => $r->tanggal_pengawasan?->format('d/m/Y'))
            ->addColumn('status_badge', fn ($r) => '<span class="badge bg-'.match ($r->status) {
                'selesai' => 'success', 'proses' => 'warning', 'tindak_lanjut' => 'danger', default => 'secondary',
            }.'">'.ucwords(str_replace('_', ' ', $r->status)).'</span>')
            ->addColumn('aksi', fn ($r) => view('ba-pencemaran.partials.aksi', ['row' => $r])->render())
            ->rawColumns(['status_badge', 'aksi'])
            ->make(true);
    }

    public function create()
    {
        $pelakuUsahas = PelakuUsaha::orderBy('nama_perusahaan')->get();
        return view('ba-pencemaran.create', compact('pelakuUsahas'));
    }

    public function store(BaPencemaranRequest $request)
    {
        $data = $request->validated();
        $data['pelaku_usaha_id'] = $this->resolvePelakuUsahaId($data['pelaku_usaha_id'] ?? null, null, $data['alamat_kantor'] ?? null);
        
        $this->sanitizeBooleans($data);
        unset($data['foto'], $data['pengawas']);

        $data['ttd_pelaku_usaha'] = $this->simpanTandaTangan($data['ttd_pelaku_usaha'] ?? null);
        $data['ttd_pengawas_1'] = $this->simpanTandaTangan($data['ttd_pengawas_1'] ?? null);
        $data['ttd_saksi_1'] = $this->simpanTandaTangan($data['ttd_saksi_1'] ?? null);
        $data['ttd_saksi_2'] = $this->simpanTandaTangan($data['ttd_saksi_2'] ?? null);

        $ba = BaPencemaran::create($data);

        foreach ($request->file('foto', []) as $foto) {
            $ba->fotos()->create(['path_foto' => $foto->store('ba-pencemaran/foto', 'public')]);
        }

        foreach ($request->input('pengawas', []) as $p) {
            if (!empty($p['nama'])) {
                $ba->pengawas()->create($p);
            }
        }

        ActivityLog::catat('Tambah', 'BA Pencemaran', "Menambahkan BA Pencemaran: {$ba->nomor_ba}");

        return redirect()->route('ba-pencemaran.index')->with('success', 'BA Pencemaran berhasil ditambahkan.');
    }

    public function show(BaPencemaran $baPencemaran)
    {
        $baPencemaran->load(['pelakuUsaha', 'fotos', 'pengawas']);
        return view('ba-pencemaran.show', compact('baPencemaran'));
    }

    public function edit(BaPencemaran $baPencemaran)
    {
        $baPencemaran->load(['pengawas']);
        $pelakuUsahas = PelakuUsaha::orderBy('nama_perusahaan')->get();
        return view('ba-pencemaran.edit', compact('baPencemaran', 'pelakuUsahas'));
    }

    public function update(BaPencemaranRequest $request, BaPencemaran $baPencemaran)
    {
        $data = $request->validated();
        $oldPelakuUsahaId = $baPencemaran->pelaku_usaha_id;
        $data['pelaku_usaha_id'] = $this->resolvePelakuUsahaId($data['pelaku_usaha_id'] ?? null, null, $data['alamat_kantor'] ?? null);
        
        $this->sanitizeBooleans($data);
        unset($data['foto'], $data['pengawas']);

        $data['ttd_pelaku_usaha'] = $this->simpanTandaTangan($data['ttd_pelaku_usaha'] ?? null);
        $data['ttd_pengawas_1'] = $this->simpanTandaTangan($data['ttd_pengawas_1'] ?? null);
        $data['ttd_saksi_1'] = $this->simpanTandaTangan($data['ttd_saksi_1'] ?? null);
        $data['ttd_saksi_2'] = $this->simpanTandaTangan($data['ttd_saksi_2'] ?? null);

        $baPencemaran->update($data);

        // Bersihkan PelakuUsaha lama jika sudah tidak dirujuk BA manapun
        $this->cleanupOrphanedPelakuUsaha($oldPelakuUsahaId, $data['pelaku_usaha_id']);

        foreach ($request->file('foto', []) as $foto) {
            $baPencemaran->fotos()->create(['path_foto' => $foto->store('ba-pencemaran/foto', 'public')]);
        }

        $baPencemaran->pengawas()->delete();
        foreach ($request->input('pengawas', []) as $p) {
            if (!empty($p['nama'])) {
                $baPencemaran->pengawas()->create($p);
            }
        }

        ActivityLog::catat('Edit', 'BA Pencemaran', "Mengubah BA Pencemaran: {$baPencemaran->nomor_ba}");

        return redirect()->route('ba-pencemaran.index')->with('success', 'BA Pencemaran berhasil diperbarui.');
    }

    public function destroy(BaPencemaran $baPencemaran)
    {
        ActivityLog::catat('Hapus', 'BA Pencemaran', "Menghapus BA Pencemaran: {$baPencemaran->nomor_ba}");
        $baPencemaran->delete();
        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus.']);
    }

    public function cetak(BaPencemaran $baPencemaran)
    {
        $baPencemaran->load(['pelakuUsaha', 'fotos', 'pengawas']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('ba-pencemaran.cetak', compact('baPencemaran'))
            ->setPaper('a4', 'portrait')
            ->setOption('isRemoteEnabled', true);

        return $pdf->stream($this->namaFileCetak($baPencemaran->nomor_ba));
    }

    private function namaFileCetak(?string $nomorBa): string
    {
        $nama = 'BA-PENCEMARAN-' . ($nomorBa ?: 'draft');
        $nama = preg_replace('/[\/\\\\:*?"<>|]+/', '-', $nama);
        $nama = preg_replace('/-+/', '-', $nama);
        return trim($nama, '-') . '.pdf';
    }



    private function sanitizeBooleans(array &$data): void
    {
        $boolFields = [
            'dugaan_pencemaran_ada', 'sampel_ada', 'kesimpulan_indikasi_pencemaran', 'kesimpulan_indikasi_pelanggaran'
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
        $filename = 'ba-pencemaran/ttd/' . Str::uuid() . '.' . $ext;
        Storage::disk('public')->put($filename, $binary);

        return $filename;
    }
}
