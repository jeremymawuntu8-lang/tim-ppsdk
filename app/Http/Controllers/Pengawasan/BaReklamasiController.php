<?php

namespace App\Http\Controllers\Pengawasan;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\BaReklamasi;
use App\Models\PelakuUsaha;
use App\Http\Requests\BaReklamasiRequest;
use App\Traits\ResolvesPelakuUsaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class BaReklamasiController extends Controller
{
    use ResolvesPelakuUsaha;

    public function index()
    {
        $pelakuUsahas = PelakuUsaha::orderBy('nama_perusahaan')->get();
        return view('ba-reklamasi.index', compact('pelakuUsahas'));
    }

    public function data(Request $request)
    {
        $query = BaReklamasi::with('pelakuUsaha')->filter($request->all());

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('perusahaan', fn ($r) => $r->pelakuUsaha->nama_perusahaan ?? $r->pelaksana_reklamasi ?? '-')
            ->addColumn('tanggal', fn ($r) => $r->tanggal_pengawasan?->format('d/m/Y'))
            ->addColumn('status_badge', fn ($r) => '<span class="badge bg-'.match ($r->status) {
                'selesai' => 'success', 'proses' => 'warning', 'tindak_lanjut' => 'danger', default => 'secondary',
            }.'">'.ucwords(str_replace('_', ' ', $r->status)).'</span>')
            ->addColumn('aksi', fn ($r) => view('ba-reklamasi.partials.aksi', ['row' => $r])->render())
            ->rawColumns(['status_badge', 'aksi'])
            ->make(true);
    }

    public function create()
    {
        $pelakuUsahas = PelakuUsaha::orderBy('nama_perusahaan')->get();
        return view('ba-reklamasi.create', compact('pelakuUsahas'));
    }

    public function store(BaReklamasiRequest $request)
    {
        $data = $request->validated();
        $data['pelaku_usaha_id'] = $this->resolvePelakuUsahaId($data['pelaku_usaha_id'] ?? null, null, $data['lokasi_reklamasi'] ?? null);
        unset($data['foto'], $data['pengawas']);

        $data['ttd_pelaku_usaha'] = $this->simpanTandaTangan($data['ttd_pelaku_usaha'] ?? null);
        $data['ttd_pengawas_1'] = $this->simpanTandaTangan($data['ttd_pengawas_1'] ?? null);
        $data['ttd_pengawas_2'] = $this->simpanTandaTangan($data['ttd_pengawas_2'] ?? null);

        $data['created_by'] = auth()->id();
        $ba = BaReklamasi::create($data);

        foreach ($request->file('foto', []) as $foto) {
            $ba->fotos()->create(['path_foto' => $foto->store('ba-reklamasi/foto', 'public')]);
        }

        foreach ($request->input('pengawas', []) as $p) {
            if (!empty($p['nama'])) {
                $p['tanda_tangan'] = $this->simpanTandaTangan($p['tanda_tangan'] ?? null);
                $ba->pengawas()->create($p);
            }
        }

        ActivityLog::catat('Tambah', 'BA REKLAMASI', "Menambahkan BA Reklamasi: {$ba->nomor_ba}");

        return redirect()->route('ba-reklamasi.index')->with('success', 'BA Reklamasi berhasil ditambahkan.');
    }

    public function show(BaReklamasi $baReklamasi)
    {
        $baReklamasi->load(['pelakuUsaha', 'fotos', 'pengawas']);
        return view('ba-reklamasi.show', compact('baReklamasi'));
    }

    public function edit(BaReklamasi $baReklamasi)
    {
        $baReklamasi->load(['pengawas']);
        $pelakuUsahas = PelakuUsaha::orderBy('nama_perusahaan')->get();
        return view('ba-reklamasi.edit', compact('baReklamasi', 'pelakuUsahas'));
    }

    public function update(BaReklamasiRequest $request, BaReklamasi $baReklamasi)
    {
        $data = $request->validated();
        $oldPelakuUsahaId = $baReklamasi->pelaku_usaha_id;
        $data['pelaku_usaha_id'] = $this->resolvePelakuUsahaId($data['pelaku_usaha_id'] ?? null, null, $data['lokasi_reklamasi'] ?? null);
        unset($data['foto'], $data['pengawas']);

        $data['ttd_pelaku_usaha'] = $this->simpanTandaTangan($data['ttd_pelaku_usaha'] ?? null);
        $data['ttd_pengawas_1'] = $this->simpanTandaTangan($data['ttd_pengawas_1'] ?? null);
        $data['ttd_pengawas_2'] = $this->simpanTandaTangan($data['ttd_pengawas_2'] ?? null);

        $baReklamasi->update($data);

        // Bersihkan PelakuUsaha lama jika sudah tidak dirujuk BA manapun
        $this->cleanupOrphanedPelakuUsaha($oldPelakuUsahaId, $data['pelaku_usaha_id']);

        foreach ($request->file('foto', []) as $foto) {
            $baReklamasi->fotos()->create(['path_foto' => $foto->store('ba-reklamasi/foto', 'public')]);
        }

        $baReklamasi->pengawas()->delete();
        foreach ($request->input('pengawas', []) as $p) {
            if (!empty($p['nama'])) {
                $p['tanda_tangan'] = $this->simpanTandaTangan($p['tanda_tangan'] ?? null);
                $baReklamasi->pengawas()->create($p);
            }
        }

        ActivityLog::catat('Edit', 'BA REKLAMASI', "Mengubah BA Reklamasi: {$baReklamasi->nomor_ba}");

        return redirect()->route('ba-reklamasi.index')->with('success', 'BA Reklamasi berhasil diperbarui.');
    }

    public function destroy(BaReklamasi $baReklamasi)
    {
        ActivityLog::catat('Hapus', 'BA REKLAMASI', "Menghapus BA Reklamasi: {$baReklamasi->nomor_ba}");
        $baReklamasi->delete();
        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus.']);
    }

    public function cetak(BaReklamasi $baReklamasi)
    {
        $baReklamasi->load(['pelakuUsaha', 'fotos', 'pengawas']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('ba-reklamasi.cetak', compact('baReklamasi'))
            ->setPaper('a4', 'portrait')
            ->setOption('isRemoteEnabled', true);

        return $pdf->stream($this->namaFileCetak($baReklamasi->nomor_ba));
    }

    private function namaFileCetak(?string $nomorBa): string
    {
        $nama = 'BA-REKLAMASI-' . ($nomorBa ?: 'draft');
        $nama = preg_replace('/[\/\\\\:*?"<>|]+/', '-', $nama);
        $nama = preg_replace('/-+/', '-', $nama);
        return trim($nama, '-') . '.pdf';
    }
    private function simpanTandaTangan(?string $value): ?string
    {
        if (empty($value)) return null;
        if (!str_starts_with($value, 'data:image/')) return $value;
        if (!preg_match('/^data:image\/(png|jpeg);base64,(.+)$/', $value, $matches)) return null;
        
        $binary = base64_decode($matches[2]);
        if ($binary === false || strlen($binary) < 100) return null;

        $ext = $matches[1] === 'jpeg' ? 'jpg' : 'png';
        $filename = 'ba-reklamasi/ttd/' . Str::uuid() . '.' . $ext;
        Storage::disk('public')->put($filename, $binary);

        return $filename;
    }
}
