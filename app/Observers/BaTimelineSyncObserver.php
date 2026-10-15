<?php

namespace App\Observers;

use App\Models\BaPencemaran;
use App\Models\BaPpk;
use App\Models\BaReklamasi;
use App\Models\BaWasAlse;
use App\Models\BaWasPrl;
use App\Models\TimelineTahapan;
use App\Models\TimelineTahapanFile;

class BaTimelineSyncObserver
{
    /**
     * Handle the "saved" event for BA Models.
     * Otomatis sinkronkan pembuatan/update BA ke Timeline Pengawasan (Tindak Lanjut).
     * Event "saved" fires for both create and update — no need for separate "created".
     */
    public function saved($model): void
    {
        $this->syncToTimeline($model);
    }

    protected function syncToTimeline($model): void
    {
        if (empty($model->pelaku_usaha_id)) {
            return;
        }

        $pelakuUsaha = $model->pelakuUsaha;
        if (!$pelakuUsaha) {
            return;
        }

        $label = match (true) {
            $model instanceof BaWasPrl => 'BA WAS PRL',
            $model instanceof BaWasAlse => 'BA WAS ALSE',
            $model instanceof BaReklamasi => 'BA Reklamasi',
            $model instanceof BaPpk => 'BA PPK',
            $model instanceof BaPencemaran => 'BA Pencemaran',
            default => 'Berita Acara Pengawasan',
        };

        $nomorBa = $model->nomor_ba ?: ('BA-' . $model->id);
        $tanggal = $model->tanggal_pengawasan ?? now();
        $statusBa = $model->status ?? 'selesai';

        if ($statusBa === 'selesai') {
            // ── STATUS SELESAI: Semua tahap langsung selesai ──
            $allStages = ['pemberitahuan', 'pengawasan', 'saran', 'keterangan', 'supervisi', 'ekspose', 'billing'];

            foreach ($allStages as $tahap) {
                $record = $pelakuUsaha->timelineTahapans()->firstOrCreate(
                    ['tahap' => $tahap],
                    [
                        'status'  => 'selesai',
                        'tanggal' => $tanggal,
                        'catatan' => "Selesai otomatis — {$label} ({$nomorBa}) berstatus Selesai",
                    ]
                );
                // Jika record sudah ada tapi belum selesai, update ke selesai
                if (!$record->wasRecentlyCreated && $record->status !== 'selesai') {
                    $record->update([
                        'status'  => 'selesai',
                        'tanggal' => $tanggal,
                        'catatan' => "Selesai otomatis — {$label} ({$nomorBa}) berstatus Selesai",
                    ]);
                }
            }
        } else {
            // ── STATUS TINDAK LANJUT: Timeline mulai dari tahap Pengawasan ──

            // 1. Selesaikan tahap Pemberitahuan
            $pemberitahuan = $pelakuUsaha->timelineTahapans()->firstOrCreate(
                ['tahap' => 'pemberitahuan'],
                [
                    'status'  => 'selesai',
                    'tanggal' => $tanggal,
                    'catatan' => "Selesai otomatis melalui penerbitan {$label} ({$nomorBa})",
                ]
            );
            if ($pemberitahuan->status === 'belum') {
                $pemberitahuan->update([
                    'status'  => 'selesai',
                    'tanggal' => $tanggal,
                    'catatan' => "Selesai otomatis melalui penerbitan {$label} ({$nomorBa})",
                ]);
            }

            // 2. Aktifkan tahap Pengawasan (proses)
            $pengawasan = $pelakuUsaha->timelineTahapans()->firstOrCreate(
                ['tahap' => 'pengawasan'],
                [
                    'status'  => 'proses',
                    'tanggal' => $tanggal,
                    'catatan' => "Telah terbit {$label}: {$nomorBa} — perlu tindak lanjut",
                ]
            );
            if ($pengawasan->status === 'belum') {
                $pengawasan->update([
                    'status'  => 'proses',
                    'tanggal' => $tanggal,
                    'catatan' => "Telah terbit {$label}: {$nomorBa} — perlu tindak lanjut",
                ]);
            }
        }

        // Jika BA memiliki file PDF, daftarkan ke TimelineTahapanFile
        if (!empty($model->file_ba_pdf)) {
            $pengawasan = $pelakuUsaha->timelineTahapans()->where('tahap', 'pengawasan')->first();
            if ($pengawasan) {
                $fileSudahAda = $pengawasan->files()->where('file_path', $model->file_ba_pdf)->exists();
                if (!$fileSudahAda) {
                    $kategoriKey = 'ba_' . strtolower(class_basename($model));
                    $pengawasan->files()->create([
                        'kategori'      => $kategoriKey,
                        'nama_dokumen'  => "{$label} ({$nomorBa})",
                        'file_path'     => $model->file_ba_pdf,
                        'file_name'     => basename($model->file_ba_pdf),
                        'mime_type'     => 'application/pdf',
                    ]);
                }
            }
        }
    }
}
