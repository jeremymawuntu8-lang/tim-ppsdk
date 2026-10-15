<?php

namespace App\Observers;

use App\Models\BaPencemaran;
use App\Models\BaPpk;
use App\Models\BaReklamasi;
use App\Models\BaWasAlse;
use App\Models\BaWasPrl;
use App\Models\PelakuUsaha;
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

        $pelakuUsaha = PelakuUsaha::find($model->pelaku_usaha_id);
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
            // ── STATUS SELESAI: Semua 7 tahap langsung selesai ──
            $allStages = ['pemberitahuan', 'pengawasan', 'saran', 'keterangan', 'supervisi', 'ekspose', 'billing'];

            foreach ($allStages as $tahap) {
                $pelakuUsaha->timelineTahapans()->updateOrCreate(
                    ['tahap' => $tahap],
                    [
                        'status'  => 'selesai',
                        'tanggal' => $tanggal,
                        'catatan' => "Selesai otomatis — {$label} ({$nomorBa}) berstatus Selesai",
                    ]
                );
            }
        } else {
            // ── STATUS TINDAK LANJUT / PROSES / DRAFT: Timeline mulai dari tahap Pengawasan ──

            // 1. Selesaikan tahap Pemberitahuan
            $pelakuUsaha->timelineTahapans()->updateOrCreate(
                ['tahap' => 'pemberitahuan'],
                [
                    'status'  => 'selesai',
                    'tanggal' => $tanggal,
                    'catatan' => "Selesai otomatis melalui penerbitan {$label} ({$nomorBa})",
                ]
            );

            // 2. Aktifkan tahap Pengawasan (proses)
            $pelakuUsaha->timelineTahapans()->updateOrCreate(
                ['tahap' => 'pengawasan'],
                [
                    'status'  => 'proses',
                    'tanggal' => $tanggal,
                    'catatan' => "Telah terbit {$label}: {$nomorBa} — perlu tindak lanjut",
                ]
            );

            // 3. Reset tahapan setelah pengawasan jika sebelumnya di-set 'Selesai otomatis'
            $subsequentStages = ['saran', 'keterangan', 'supervisi', 'ekspose', 'billing'];
            foreach ($subsequentStages as $st) {
                $rec = $pelakuUsaha->timelineTahapans()->where('tahap', $st)->first();
                if ($rec && str_contains($rec->catatan ?? '', 'Selesai otomatis')) {
                    $rec->update([
                        'status'  => 'belum',
                        'catatan' => null,
                    ]);
                }
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
