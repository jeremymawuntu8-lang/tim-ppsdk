<?php

namespace App\Traits;

use App\Models\PelakuUsaha;

trait ResolvesPelakuUsaha
{
    /**
     * Resolves PelakuUsaha ID from form value, taking location into account.
     *
     * Aturan:
     * 1. Jika user memilih dari dropdown (value = numeric ID):
     *    - Jika provinsi_id BA sama dengan PelakuUsaha yang dipilih, gunakan apa adanya
     *    - Jika provinsi_id BA BERBEDA, buat PelakuUsaha baru dengan nama sama tapi provinsi berbeda
     * 2. Jika user mengetik nama baru (value = string):
     *    - Cari PelakuUsaha berdasarkan nama + provinsi
     *    - Jika tidak ada, buat baru
     */
    protected function resolvePelakuUsahaId(?string $value, ?int $provinsiId = null, ?string $alamat = null): ?int
    {
        if (empty($value)) {
            return null;
        }

        // Jika user memilih dari dropdown (value adalah ID numeric)
        if (is_numeric($value) && ($existing = PelakuUsaha::find((int) $value))) {
            // Jika provinsi BA tidak diisi, atau sama dengan PelakuUsaha yang dipilih -> gunakan apa adanya
            if (empty($provinsiId) || $existing->provinsi_id == $provinsiId || empty($existing->provinsi_id)) {
                // Jika PelakuUsaha belum punya provinsi, update dari BA
                if (empty($existing->provinsi_id) && !empty($provinsiId)) {
                    $existing->updateQuietly(['provinsi_id' => $provinsiId]);
                }
                return $existing->id;
            }

            // Provinsi BERBEDA -> buat PelakuUsaha baru dengan nama sama tapi lokasi berbeda
            $newPu = PelakuUsaha::firstOrCreate(
                [
                    'nama_perusahaan' => $existing->nama_perusahaan,
                    'provinsi_id' => $provinsiId,
                ],
                [
                    'alamat' => $alamat,
                    'status' => 'aktif',
                    'created_by' => auth()->id()
                ]
            );

            return $newPu->id;
        }

        // Jika user mengetik nama baru (string)
        $nama = trim($value);
        if ($nama === '') {
            return null;
        }

        $pelakuUsaha = PelakuUsaha::firstOrCreate(
            [
                'nama_perusahaan' => $nama,
                'provinsi_id' => $provinsiId,
            ],
            [
                'alamat' => $alamat,
                'status' => 'aktif',
                'created_by' => auth()->id()
            ]
        );

        return $pelakuUsaha->id;
    }

    /**
     * Bersihkan koordinat PelakuUsaha lama yang sudah tidak dirujuk BA manapun.
     *
     * Dipanggil saat update BA dan pelaku_usaha_id berubah. Jika PelakuUsaha
     * lama tidak lagi direferensikan oleh BA WAS PRL, ALSE, PPK, Pencemaran,
     * maupun Reklamasi, maka koordinatnya dihapus agar tidak muncul duplikat
     * di peta.
     */
    protected function cleanupOrphanedPelakuUsaha(?int $oldId, ?int $newId): void
    {
        // Tidak perlu cleanup jika ID tidak berubah atau tidak ada ID lama
        if (empty($oldId) || $oldId === $newId) {
            return;
        }

        $old = PelakuUsaha::find($oldId);
        if (!$old) {
            return;
        }

        // Cek apakah masih dirujuk oleh BA manapun
        $stillReferenced = $old->baWasPrls()->exists()
            || $old->baWasAlses()->exists()
            || $old->baPpks()->exists()
            || $old->baPencemarans()->exists()
            || $old->baReklamasis()->exists();

        if (!$stillReferenced) {
            // Hapus koordinat agar tidak muncul di peta
            $old->updateQuietly([
                'latitude'  => null,
                'longitude' => null,
            ]);
        }
    }
}
