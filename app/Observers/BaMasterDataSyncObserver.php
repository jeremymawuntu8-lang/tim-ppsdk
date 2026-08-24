<?php

namespace App\Observers;

use App\Models\JenisUsaha;
use App\Models\PelakuUsaha;
use Illuminate\Support\Str;

class BaMasterDataSyncObserver
{
    /**
     * Handle the "saved" event for BA Models.
     */
    public function saved($model): void
    {
        $this->syncMasterData($model);
    }

    private function syncMasterData($model): void
    {
        if (empty($model->pelaku_usaha_id)) {
            return;
        }

        $pelakuUsaha = $model->pelakuUsaha;
        if (!$pelakuUsaha) {
            return;
        }

        $jenisUsahasFromBa = $model->jenis_usaha ?? $model->jenis_kegiatan_usaha ?? $model->jenis_pemanfaatan_reklamasi;
        if (empty($jenisUsahasFromBa)) {
            return;
        }

        $firstJenisUsahaId = null;
        $items = is_array($jenisUsahasFromBa) ? $jenisUsahasFromBa : [$jenisUsahasFromBa];

        foreach ($items as $key => $value) {
            // Handle both flat arrays and associative arrays (checkboxes)
            if (is_string($value) && $value !== '1' && $value !== 'true') {
                $nama = trim($value);
            } elseif (is_string($key)) {
                $nama = ucwords(str_replace('_', ' ', trim($key)));
            } else {
                continue;
            }

            if (!empty($nama)) {
                // Ensure Master Data exists
                $jenisUsaha = JenisUsaha::firstOrCreate(
                    ['nama' => $nama],
                    ['kode' => null]
                );
                
                if (!$firstJenisUsahaId) {
                    $firstJenisUsahaId = $jenisUsaha->id;
                }
            }
        }

        // 1. Sync Jenis Usaha
        // Link the first discovered JenisUsaha to the PelakuUsaha if not already linked
        $updates = [];
        if ($firstJenisUsahaId && empty($pelakuUsaha->jenis_usaha_id)) {
            $updates['jenis_usaha_id'] = $firstJenisUsahaId;
        }

        // 2. Sync Location Fields (Provinsi, Alamat, Koordinat) if empty
        if (empty($pelakuUsaha->provinsi_id) && !empty($model->provinsi_id)) {
            $updates['provinsi_id'] = $model->provinsi_id;
        }

        if (empty($pelakuUsaha->alamat)) {
            $alamat = $model->alamat_kantor ?? $model->alamat_pj ?? $model->lokasi ?? $model->lokasi_pengawasan ?? $model->lokasi_reklamasi ?? null;
            if (!empty($alamat)) {
                $updates['alamat'] = $alamat;
            }
        }

        if (empty($pelakuUsaha->latitude) && !empty($model->latitude)) {
            $updates['latitude'] = $model->latitude;
        }

        if (empty($pelakuUsaha->longitude) && !empty($model->longitude)) {
            $updates['longitude'] = $model->longitude;
        }
        
        // Save updates quietly if any
        if (!empty($updates)) {
            $pelakuUsaha->updateQuietly($updates);
        }
    }
}
