<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait HandlesArsipDokumenBa
{
    /**
     * Simpan arsip dokumen lama (file dan link Google Drive) dari form BA.
     */
    protected function simpanArsipDokumenDariForm($ba, Request $request): void
    {
        if ($request->filled('link_gdrive_arsip')) {
            $rawLink = trim($request->input('link_gdrive_arsip'));
            if (!preg_match('~^(?:f|ht)tps?://~i', $rawLink)) {
                $rawLink = 'https://' . $rawLink;
            }

            $ba->arsipDokumen()->create([
                'tipe' => 'link',
                'judul' => 'Folder Arsip Dokumen Google Drive',
                'link_gdrive' => $rawLink,
                'keterangan' => $request->input('keterangan_arsip'),
                'uploaded_by' => auth()->id(),
            ]);
        }

        if ($request->hasFile('files_arsip')) {
            foreach ($request->file('files_arsip') as $file) {
                $ba->arsipDokumen()->create([
                    'tipe' => 'file',
                    'judul' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                    'nama_file' => $file->getClientOriginalName(),
                    'path_file' => $file->store('arsip-dokumen-ba', 'public'),
                    'keterangan' => $request->input('keterangan_arsip'),
                    'uploaded_by' => auth()->id(),
                ]);
            }
        }
    }
}
