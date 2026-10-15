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

        // 1. Selesaikan tahap Pemberitahuan (jika belum selesai)
        $pemberitahuan = $pelakuUsaha->timelineTahapans()->firstOrCreate(
            ['tahap' => 'pemberitahuan'],
            [
                'status' => 'selesai',
                'tanggal' => $tanggal,
                'catatan' => "Selesai otomatis melalui penerbitan {$label} ({$nomorBa})",
            ]
        );
        if ($pemberitahuan->status === 'belum') {
            $pemberitahuan->update([
                'status' => 'selesai',
                'tanggal' => $tanggal,
                'catatan' => "Selesai otomatis melalui penerbitan {$label} ({$nomorBa})",
            ]);
        }

        // 2. Aktifkan tahap Pengawasan (status: proses atau sesuai status BA)
        $pengawasan = $pelakuUsaha->timelineTahapans()->firstOrCreate(
            ['tahap' => 'pengawasan'],
            [
                'status' => 'proses',
                'tanggal' => $tanggal,
                'catatan' => "Telah terbit {$label}: {$nomorBa}",
            ]
        );
        if ($pengawasan->status === 'belum') {
            $pengawasan->update([
                'status' => 'proses',
                'tanggal' => $tanggal,
                'catatan' => "Telah terbit {$label}: {$nomorBa}",
            ]);
        }

        // 3. Jika BA memiliki file PDF, daftarkan juga ke TimelineTahapanFile jika belum ada
        if (!empty($model->file_ba_pdf)) {
            $fileSudahAda = $pengawasan->files()->where('file_path', $model->file_ba_pdf)->exists();
            if (!$fileSudahAda) {
                $kategoriKey = 'ba_' . strtolower(class_basename($model));
                $pengawasan->files()->create([
                    'kategori' => $kategoriKey,
                    'nama_dokumen' => "{$label} ({$nomorBa})",
                    'file_path' => $model->file_ba_pdf,
                    'file_name' => basename($model->file_ba_pdf),
                    'mime_type' => 'application/pdf',
                ]);
            }
        }
    }
}
