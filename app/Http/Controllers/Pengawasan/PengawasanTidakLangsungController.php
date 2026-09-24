<?php

namespace App\Http\Controllers\Pengawasan;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\PengawasanTidakLangsung;
use App\Models\PelakuUsaha;
use App\Http\Requests\PengawasanTidakLangsungRequest;
use App\Traits\ResolvesPelakuUsaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PengawasanTidakLangsungController extends Controller
{
    use ResolvesPelakuUsaha;

    public function index()
    {
        $pelakuUsahas = PelakuUsaha::orderBy('nama_perusahaan')->get();
        return view('pengawasan-tidak-langsung.index', compact('pelakuUsahas'));
    }

    public function data(Request $request)
    {
        $query = PengawasanTidakLangsung::with('pelakuUsaha')->filter($request->all());

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nomor', fn ($r) => $r->nomor ?? '-')
            ->addColumn('perusahaan', fn ($r) => $r->pelakuUsaha->nama_perusahaan ?? '-')
            ->addColumn('tanggal_laporan', fn ($r) => $r->tanggal_laporan?->format('d/m/Y'))
            ->addColumn('status_badge', fn ($r) => '<span class="badge bg-'.match ($r->status) {
                'selesai' => 'success', 'proses' => 'warning', 'tindak_lanjut' => 'danger', default => 'secondary',
            }.'">'.ucwords(str_replace('_', ' ', $r->status)).'</span>')
            ->addColumn('aksi', fn ($r) => view('pengawasan-tidak-langsung.partials.aksi', ['row' => $r])->render())
            ->rawColumns(['status_badge', 'aksi'])
            ->make(true);
    }

    public function create()
    {
        $pelakuUsahas = PelakuUsaha::orderBy('nama_perusahaan')->get();
        return view('pengawasan-tidak-langsung.create', compact('pelakuUsahas'));
    }

    public function store(PengawasanTidakLangsungRequest $request)
    {
        $data = $request->validated();
        
        // Handle pelaku usaha
        $data['pelaku_usaha_id'] = $this->resolvePelakuUsahaId($data['pelaku_usaha_id'] ?? null, null, $data['lokasi'] ?? null);
        
        $data['kepala_upt_ttd'] = $this->simpanTandaTangan($data['kepala_upt_ttd'] ?? null);
        
        if (empty($data['nomor'])) {
            $data['nomor'] = 'PTL-' . date('Ymd') . '-' . strtoupper(Str::random(5));
        }

        $data['created_by'] = auth()->id();
        
        // Extract array fields
        $kesesuaian = $data['kesesuaian'] ?? [];
        unset($data['kesesuaian']);

        $ba = PengawasanTidakLangsung::create($data);

        // Save images
        for ($i = 1; $i <= 8; $i++) {
            $gambarKey = 'gambar_' . $i;
            if ($request->hasFile($gambarKey)) {
                $path = $request->file($gambarKey)->store('pengawasan-tidak-langsung/gambar', 'public');
                $ba->gambars()->create([
                    'kategori' => (string) $i,
                    'path_file' => $path
                ]);
            }
        }

        // Save kesesuaian
        foreach ($kesesuaian as $item) {
            if (!empty($item['alokasi_ruang']) || !empty($item['kegiatan'])) {
                $ba->kesesuaian()->create($item);
            }
        }

        ActivityLog::catat('Tambah', 'Pengawasan Tidak Langsung', "Menambahkan Pengawasan Tidak Langsung: {$ba->nomor}");

        return redirect()->route('pengawasan-tidak-langsung.index')->with('success', 'Form Pengawasan Tidak Langsung berhasil ditambahkan.');
    }

    public function show(PengawasanTidakLangsung $pengawasanTidakLangsung)
    {
        $pengawasanTidakLangsung->load(['pelakuUsaha', 'gambars', 'kesesuaian']);
        return view('pengawasan-tidak-langsung.show', compact('pengawasanTidakLangsung'));
    }

    public function edit(PengawasanTidakLangsung $pengawasanTidakLangsung)
    {
        $pengawasanTidakLangsung->load(['gambars', 'kesesuaian']);
        $pelakuUsahas = PelakuUsaha::orderBy('nama_perusahaan')->get();
        return view('pengawasan-tidak-langsung.edit', compact('pengawasanTidakLangsung', 'pelakuUsahas'));
    }

    public function update(PengawasanTidakLangsungRequest $request, PengawasanTidakLangsung $pengawasanTidakLangsung)
    {
        $data = $request->validated();
        
        $oldPelakuUsahaId = $pengawasanTidakLangsung->pelaku_usaha_id;
        $data['pelaku_usaha_id'] = $this->resolvePelakuUsahaId($data['pelaku_usaha_id'] ?? null, null, $data['lokasi'] ?? null);
        
        $data['kepala_upt_ttd'] = $this->simpanTandaTangan($data['kepala_upt_ttd'] ?? null);
        
        if (empty($data['nomor'])) {
            $data['nomor'] = 'PTL-' . date('Ymd') . '-' . strtoupper(Str::random(5));
        }

        $kesesuaian = $data['kesesuaian'] ?? [];
        unset($data['kesesuaian']);

        $pengawasanTidakLangsung->update($data);

        // Cleanup orphaned
        $this->cleanupOrphanedPelakuUsaha($oldPelakuUsahaId, $data['pelaku_usaha_id']);

        // Save new images
        for ($i = 1; $i <= 8; $i++) {
            $gambarKey = 'gambar_' . $i;
            if ($request->hasFile($gambarKey)) {
                $path = $request->file($gambarKey)->store('pengawasan-tidak-langsung/gambar', 'public');
                // Delete old image if exists
                $oldGambar = $pengawasanTidakLangsung->gambars()->where('kategori', (string)$i)->first();
                if ($oldGambar) {
                    Storage::disk('public')->delete($oldGambar->path_file);
                    $oldGambar->delete();
                }
                $pengawasanTidakLangsung->gambars()->create([
                    'kategori' => (string) $i,
                    'path_file' => $path
                ]);
            }
        }

        // Sync kesesuaian
        $pengawasanTidakLangsung->kesesuaian()->delete();
        foreach ($kesesuaian as $item) {
            if (!empty($item['alokasi_ruang']) || !empty($item['kegiatan'])) {
                $pengawasanTidakLangsung->kesesuaian()->create($item);
            }
        }

        ActivityLog::catat('Edit', 'Pengawasan Tidak Langsung', "Mengubah Pengawasan Tidak Langsung: {$pengawasanTidakLangsung->nomor}");

        return redirect()->route('pengawasan-tidak-langsung.index')->with('success', 'Form Pengawasan Tidak Langsung berhasil diperbarui.');
    }

    public function destroy(PengawasanTidakLangsung $pengawasanTidakLangsung)
    {
        // Delete images from storage
        foreach ($pengawasanTidakLangsung->gambars as $gambar) {
            Storage::disk('public')->delete($gambar->path_file);
        }
        
        ActivityLog::catat('Hapus', 'Pengawasan Tidak Langsung', "Menghapus Pengawasan Tidak Langsung: {$pengawasanTidakLangsung->nomor}");
        $pengawasanTidakLangsung->delete();

        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus.']);
    }

    public function cetak(PengawasanTidakLangsung $pengawasanTidakLangsung)
    {
        $pengawasanTidakLangsung->load(['pelakuUsaha', 'gambars', 'kesesuaian']);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pengawasan-tidak-langsung.cetak', compact('pengawasanTidakLangsung'))
            ->setPaper('a4', 'portrait')
            ->setOption('isRemoteEnabled', true);

        return $pdf->stream($this->namaFileCetak($pengawasanTidakLangsung->nomor));
    }

    private function namaFileCetak(?string $nomor): string
    {
        $nama = 'PTL-' . ($nomor ?: 'draft');
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
        $filename = 'pengawasan-tidak-langsung/ttd/' . Str::uuid() . '.' . $ext;
        Storage::disk('public')->put($filename, $binary);

        return $filename;
    }
}
