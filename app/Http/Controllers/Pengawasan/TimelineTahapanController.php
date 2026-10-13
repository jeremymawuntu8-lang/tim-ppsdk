<?php

namespace App\Http\Controllers\Pengawasan;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\BaPencemaran;
use App\Models\BaPpk;
use App\Models\BaReklamasi;
use App\Models\BaWasAlse;
use App\Models\BaWasPrl;
use App\Models\PelakuUsaha;
use App\Models\TimelineTahapan;
use App\Models\TimelineTahapanFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TimelineTahapanController extends Controller
{
    public function upload(Request $request, PelakuUsaha $pelakuUsaha)
    {
        $tahap = $request->input('tahap', 'supervisi');
        $kategori = $request->input('kategori');
        $slot = $request->input('slot');
        $namaDokumen = $request->input('nama_dokumen');

        $tahapan = $pelakuUsaha->timelineTahapans()->firstOrCreate(
            ['tahap' => $tahap],
            ['status' => 'proses']
        );
        if ($tahapan->status === 'belum') {
            $tahapan->update(['status' => 'proses']);
        }

        // ==================== KHUSUS TAHAP PENGAWASAN: SINKRONISASI KE MODEL BA ====================
        if ($tahap === 'pengawasan') {
            $sudahAdaBa = collect(\App\Services\TimelinePengawasanService::BA_TYPES)
                ->keys()
                ->contains(fn ($rel) => $pelakuUsaha->{$rel}()->exists());
            if ($sudahAdaBa) {
                return redirect()->route('timeline-pengawasan.show', ['pelakuUsaha' => $pelakuUsaha->id, 'tab' => 'pengawasan'])
                    ->with('error', 'Berita Acara untuk pelaku usaha ini sudah tersinkron dari menu Pengawasan, tidak perlu diunggah lagi.');
            }

            $request->validate([
                'file' => 'required|file|max:20480',
            ]);

            $jenisBa = strtolower((string) $request->input('jenis_ba', 'prl'));
            $nomorBaInput = trim((string) $request->input('nomor_ba'));
            $tanggalPengawasan = $request->input('tanggal_pengawasan') ?: now()->toDateString();
            $catatanBa = $request->input('catatan');

            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $mimeType = $file->getMimeType();

            $folder = match ($jenisBa) {
                'prl' => 'ba-was-prl',
                'alse' => 'ba-was-alse',
                'reklamasi' => 'ba-reklamasi',
                'ppk' => 'ba-ppk',
                'pencemaran' => 'ba-pencemaran',
                default => 'timeline_files/' . $pelakuUsaha->id,
            };
            $path = $file->store($folder, 'public');

            $prefix = match ($jenisBa) {
                'prl' => 'BA-PRL-',
                'alse' => 'BA-ALSE-',
                'reklamasi' => 'BA-REK-',
                'ppk' => 'BA-PPK-',
                'pencemaran' => 'BA-PCM-',
                default => 'BA-',
            };

            $nomorBa = $nomorBaInput ?: ($prefix . date('Ymd') . '-' . strtoupper(Str::random(5)));
            $labelBa = match ($jenisBa) {
                'prl' => 'BA WAS PRL',
                'alse' => 'BA WAS ALSE',
                'reklamasi' => 'BA Reklamasi',
                'ppk' => 'BA PPK',
                'pencemaran' => 'BA Pencemaran',
                default => 'Berita Acara Pengawasan',
            };

            $docTitle = $namaDokumen ?: "{$labelBa} ({$nomorBa})";

            // Simpan ke record BA terkait di database sistem utama
            $createdBa = null;
            if ($jenisBa === 'prl') {
                $createdBa = BaWasPrl::create([
                    'pelaku_usaha_id' => $pelakuUsaha->id,
                    'nomor_ba' => $nomorBa,
                    'tanggal_pengawasan' => $tanggalPengawasan,
                    'nomor_pkkprl' => $pelakuUsaha->nomor_pkkprl,
                    'lokasi' => $pelakuUsaha->alamat ?: ($pelakuUsaha->kabupaten?->nama ?? '-'),
                    'provinsi_id' => $pelakuUsaha->provinsi_id,
                    'status' => 'selesai',
                    'file_ba_pdf' => $path,
                    'created_by' => auth()->id(),
                    'hasil_pengawasan' => $catatanBa ?: 'Dokumen BA diunggah melalui Timeline Pengawasan',
                    'kesimpulan' => 'Dokumen Berita Acara terunggah dan tercatat melalui Timeline Pengawasan.',
                ]);
            } elseif ($jenisBa === 'alse') {
                $createdBa = BaWasAlse::create([
                    'pelaku_usaha_id' => $pelakuUsaha->id,
                    'nomor_ba' => $nomorBa,
                    'tanggal_pengawasan' => $tanggalPengawasan,
                    'nomor_pkkprl' => $pelakuUsaha->nomor_pkkprl,
                    'provinsi_id' => $pelakuUsaha->provinsi_id,
                    'status' => 'selesai',
                    'file_ba_pdf' => $path,
                    'created_by' => auth()->id(),
                    'hasil_pengawasan' => $catatanBa ?: 'Dokumen BA diunggah melalui Timeline Pengawasan',
                    'kesimpulan' => 'Dokumen Berita Acara terunggah dan tercatat melalui Timeline Pengawasan.',
                ]);
            } elseif ($jenisBa === 'reklamasi') {
                $createdBa = BaReklamasi::create([
                    'pelaku_usaha_id' => $pelakuUsaha->id,
                    'nomor_ba' => $nomorBa,
                    'tanggal_pengawasan' => $tanggalPengawasan,
                    'status' => 'selesai',
                    'file_ba_pdf' => $path,
                    'created_by' => auth()->id(),
                    'lokasi_reklamasi' => $pelakuUsaha->alamat ?: ($pelakuUsaha->kabupaten?->nama ?? '-'),
                ]);
            } elseif ($jenisBa === 'ppk') {
                $createdBa = BaPpk::create([
                    'pelaku_usaha_id' => $pelakuUsaha->id,
                    'nomor_ba' => $nomorBa,
                    'tanggal_pengawasan' => $tanggalPengawasan,
                    'status' => 'selesai',
                    'file_ba_pdf' => $path,
                    'created_by' => auth()->id(),
                    'lokasi' => $pelakuUsaha->alamat ?: ($pelakuUsaha->kabupaten?->nama ?? '-'),
                ]);
            } elseif ($jenisBa === 'pencemaran') {
                $createdBa = BaPencemaran::create([
                    'pelaku_usaha_id' => $pelakuUsaha->id,
                    'nomor_ba' => $nomorBa,
                    'tanggal_pengawasan' => $tanggalPengawasan,
                    'status' => 'selesai',
                    'file_ba_pdf' => $path,
                    'created_by' => auth()->id(),
                    'lokasi' => $pelakuUsaha->alamat ?: ($pelakuUsaha->kabupaten?->nama ?? '-'),
                ]);
            }

            if ($createdBa) {
                $createdBa->arsipDokumen()->create([
                    'tipe' => 'file',
                    'judul' => $docTitle,
                    'path_file' => $path,
                    'nama_file' => $originalName,
                    'uploaded_by' => auth()->id(),
                    'keterangan' => 'Diunggah melalui Timeline Pengawasan',
                ]);
            }

            // Simpan juga ke timeline files
            $tahapan->files()->create([
                'kategori' => 'ba_' . $jenisBa,
                'nama_dokumen' => $docTitle,
                'file_path' => $path,
                'file_name' => $originalName,
                'file_size' => $fileSize,
                'mime_type' => $mimeType,
            ]);

            // Selesaikan tahap pemberitahuan jika belum
            $pemberitahuan = $pelakuUsaha->timelineTahapans()->where('tahap', 'pemberitahuan')->first();
            if (!$pemberitahuan || $pemberitahuan->status === 'belum') {
                $pelakuUsaha->timelineTahapans()->updateOrCreate(
                    ['tahap' => 'pemberitahuan'],
                    [
                        'status' => 'selesai',
                        'tanggal' => $tanggalPengawasan,
                        'catatan' => "Selesai otomatis (BA diunggah: {$nomorBa})",
                    ]
                );
            }

            $tahapan->update([
                'status' => 'proses',
                'tanggal' => $tanggalPengawasan,
                'catatan' => "Telah diunggah {$labelBa}: {$nomorBa}",
            ]);

            ActivityLog::catat('Upload', 'Timeline Pengawasan', "Mengunggah {$labelBa} ({$nomorBa}) untuk {$pelakuUsaha->nama_perusahaan}");

            return redirect()->route('timeline-pengawasan.show', ['pelakuUsaha' => $pelakuUsaha->id, 'tab' => 'pengawasan'])
                ->with('success', "Dokumen {$labelBa} ({$nomorBa}) berhasil diunggah! Data otomatis tersimpan dan bertambah di sistem pengawasan {$labelBa}.");
        }

        // 1. Upload File Tunggal untuk tahap lainnya
        if ($request->hasFile('file')) {
            $request->validate([
                'file' => 'required|file|max:20480', // 20MB
            ]);

            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $mimeType = $file->getMimeType();

            if ($kategori === 'bahan_paparan') {
                $old = $tahapan->files()->where('kategori', 'bahan_paparan')->first();
                if ($old) {
                    Storage::disk('public')->delete($old->file_path);
                    $old->delete();
                }
                $path = $file->store('timeline_files/' . $pelakuUsaha->id, 'public');
                $tahapan->files()->create([
                    'kategori' => 'bahan_paparan',
                    'nama_dokumen' => $namaDokumen ?: 'Bahan Paparan',
                    'file_path' => $path,
                    'file_name' => $originalName,
                    'file_size' => $fileSize,
                    'mime_type' => $mimeType,
                ]);
            } elseif ($kategori === 'permohonan_supervisi') {
                $old = $tahapan->files()->where('kategori', 'permohonan_supervisi')->first();
                if ($old) {
                    Storage::disk('public')->delete($old->file_path);
                    $old->delete();
                }
                $path = $file->store('timeline_files/' . $pelakuUsaha->id, 'public');
                $tahapan->files()->create([
                    'kategori' => 'permohonan_supervisi',
                    'nama_dokumen' => $namaDokumen ?: 'Permohonan Supervisi',
                    'file_path' => $path,
                    'file_name' => $originalName,
                    'file_size' => $fileSize,
                    'mime_type' => $mimeType,
                ]);
            } elseif ($kategori === 'peta_spasial') {
                $slotNum = $slot ? (int) $slot : 1;
                $old = $tahapan->files()->where('kategori', 'peta_spasial')->where('slot', $slotNum)->first();
                if ($old) {
                    Storage::disk('public')->delete($old->file_path);
                    $old->delete();
                }
                $path = $file->store('timeline_files/' . $pelakuUsaha->id . '/peta_spasial', 'public');
                $tahapan->files()->create([
                    'kategori' => 'peta_spasial',
                    'slot' => $slotNum,
                    'nama_dokumen' => $namaDokumen ?: ('Foto ' . $slotNum),
                    'file_path' => $path,
                    'file_name' => $originalName,
                    'file_size' => $fileSize,
                    'mime_type' => $mimeType,
                ]);
            } else {
                $path = $file->store('timeline_files/' . $pelakuUsaha->id, 'public');
                $tahapan->files()->create([
                    'kategori' => $kategori ?: 'dokumen_tambahan',
                    'nama_dokumen' => $namaDokumen ?: $originalName,
                    'file_path' => $path,
                    'file_name' => $originalName,
                    'file_size' => $fileSize,
                    'mime_type' => $mimeType,
                ]);
            }
        }

        // 2. Upload Banyak Foto (Bulk Peta Spasial)
        if ($request->hasFile('files')) {
            $request->validate([
                'files.*' => 'file|max:20480',
            ]);

            if ($kategori === 'peta_spasial') {
                $existingSlots = $tahapan->files()->where('kategori', 'peta_spasial')->pluck('slot')->toArray();
                $uploadedFiles = $request->file('files');
                $availableSlots = array_values(array_diff(range(1, 8), $existingSlots));

                foreach ($uploadedFiles as $idx => $f) {
                    if (empty($availableSlots)) {
                        break;
                    }
                    $targetSlot = array_shift($availableSlots);
                    $path = $f->store('timeline_files/' . $pelakuUsaha->id . '/peta_spasial', 'public');
                    $tahapan->files()->create([
                        'kategori' => 'peta_spasial',
                        'slot' => $targetSlot,
                        'nama_dokumen' => 'Foto ' . $targetSlot,
                        'file_path' => $path,
                        'file_name' => $f->getClientOriginalName(),
                        'file_size' => $f->getSize(),
                        'mime_type' => $f->getMimeType(),
                    ]);
                }
            }
        }

        if ($request->input('action_type') === 'submit' || $request->has('tandai_selesai')) {
            $tahapan->update([
                'status' => 'selesai',
                'tanggal' => now(),
            ]);
            return redirect()->route('timeline-pengawasan.show', ['pelakuUsaha' => $pelakuUsaha->id, 'tab' => $tahap])
                ->with('success', 'Dokumen berhasil diunggah dan tahap ' . ucfirst($tahap) . ' ditandai selesai.');
        }

        return redirect()->route('timeline-pengawasan.show', ['pelakuUsaha' => $pelakuUsaha->id, 'tab' => $tahap])
            ->with('success', 'Dokumen berhasil diunggah.');
    }

    public function selesaikanSupervisi(Request $request, PelakuUsaha $pelakuUsaha)
    {
        // Pastikan tahap sebelumnya tercatat selesai jika belum ada
        $priorStages = ['pemberitahuan', 'pengawasan', 'saran', 'keterangan'];
        foreach ($priorStages as $stg) {
            $existing = $pelakuUsaha->timelineTahapans()->where('tahap', $stg)->first();
            if (!$existing) {
                $pelakuUsaha->timelineTahapans()->create([
                    'tahap' => $stg,
                    'status' => 'selesai',
                    'tanggal' => now()
                ]);
            }
        }

        // Tandai supervisi selesai
        $supervisi = $pelakuUsaha->timelineTahapans()->firstOrCreate(
            ['tahap' => 'supervisi'],
            ['status' => 'proses']
        );
        $supervisi->update([
            'status' => 'selesai',
            'tanggal' => now(),
        ]);

        // Sesuai permintaan user: jika supervisi sudah selesai maka ekspose juga sudah selesai
        $ekspose = $pelakuUsaha->timelineTahapans()->firstOrCreate(
            ['tahap' => 'ekspose'],
            ['status' => 'proses']
        );
        $ekspose->update([
            'status' => 'selesai',
            'tanggal' => now(),
        ]);

        return redirect()->route('timeline-pengawasan.show', ['pelakuUsaha' => $pelakuUsaha->id, 'tab' => 'ekspose'])
            ->with('success', 'Tahap Supervisi & Ekspose berhasil diselesaikan. Silakan lanjut ke tahap Billing.');
    }

    public function skipTahap(Request $request, PelakuUsaha $pelakuUsaha)
    {
        $request->validate([
            'tahap' => 'required|string',
        ]);

        $tahap = $request->tahap;
        $stageKeys = array_keys(\App\Services\TimelinePengawasanService::STAGES);
        $currentIdx = array_search($tahap, $stageKeys, true);
        $nextTahap = ($currentIdx !== false && isset($stageKeys[$currentIdx + 1])) ? $stageKeys[$currentIdx + 1] : 'billing';

        $tahapan = $pelakuUsaha->timelineTahapans()->firstOrCreate(
            ['tahap' => $tahap],
            ['status' => 'skip']
        );
        $tahapan->update([
            'status' => 'skip',
            'catatan' => 'Dilewati secara manual (di luar sistem)',
            'tanggal' => now(),
        ]);

        // Jika supervisi di-skip, selaraskan ekspose
        if ($tahap === 'supervisi') {
            $ekspose = $pelakuUsaha->timelineTahapans()->firstOrCreate(
                ['tahap' => 'ekspose'],
                ['status' => 'skip']
            );
            $ekspose->update([
                'status' => 'skip',
                'catatan' => 'Dilewati secara manual bersama supervisi',
                'tanggal' => now(),
            ]);
            $nextTahap = 'ekspose';
        }

        $label = \App\Services\TimelinePengawasanService::STAGES[$tahap]['label'] ?? ucfirst($tahap);
        return redirect()->route('timeline-pengawasan.show', [
            'pelakuUsaha' => $pelakuUsaha->id,
            'tab' => $nextTahap,
        ])->with('success', "Tahap {$label} berhasil dilewati secara manual.");
    }

    public function lanjutBilling(Request $request, PelakuUsaha $pelakuUsaha)
    {
        // Tandai ekspose selesai
        $ekspose = $pelakuUsaha->timelineTahapans()->firstOrCreate(
            ['tahap' => 'ekspose'],
            ['status' => 'proses']
        );
        $ekspose->update([
            'status' => 'selesai',
            'tanggal' => now(),
        ]);

        // Inisialisasi billing
        $pelakuUsaha->timelineTahapans()->firstOrCreate(
            ['tahap' => 'billing'],
            ['status' => 'proses']
        );

        return redirect()->route('timeline-pengawasan.show', ['pelakuUsaha' => $pelakuUsaha->id, 'tab' => 'billing'])
            ->with('success', 'Tahap Ekspose berhasil diselesaikan. Melanjutkan ke tahap Billing.');
    }

    public function updateStatus(Request $request, PelakuUsaha $pelakuUsaha)
    {
        $request->validate([
            'tahap' => 'required|string',
            'status' => 'required|string',
        ]);

        $tahapan = $pelakuUsaha->timelineTahapans()->firstOrCreate(
            ['tahap' => $request->tahap],
            ['status' => $request->status]
        );

        $tahapan->update([
            'status' => $request->status,
            'catatan' => $request->catatan,
            'tanggal' => $request->status === 'selesai' ? ($request->tanggal ?: now()) : null,
        ]);

        $nextTab = $request->input('next_tab', $request->tahap);

        return redirect()->route('timeline-pengawasan.show', ['pelakuUsaha' => $pelakuUsaha->id, 'tab' => $nextTab])
            ->with('success', 'Status tahap ' . (\App\Services\TimelinePengawasanService::STAGES[$request->tahap]['label'] ?? ucfirst($request->tahap)) . ' berhasil diperbarui');
    }

    public function deleteFile(TimelineTahapanFile $file)
    {
        Storage::disk('public')->delete($file->file_path);
        $file->delete();

        return back()->with('success', 'Dokumen berhasil dihapus');
    }
}
