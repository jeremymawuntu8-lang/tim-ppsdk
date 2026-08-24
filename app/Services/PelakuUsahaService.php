<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\PelakuUsaha;
use App\Models\PelakuUsahaDokumen;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PelakuUsahaService
{
    public function create(array $data, ?UploadedFile $foto, array $dokumenFiles = [], array $jenisDokumen = []): PelakuUsaha
    {
        return DB::transaction(function () use ($data, $foto, $dokumenFiles, $jenisDokumen) {
            if ($foto) {
                $data['foto_lokasi'] = $foto->store('pelaku-usaha/foto-lokasi', 'public');
            }

            $data['created_by'] = auth()->id();

            $pelakuUsaha = PelakuUsaha::create($data);

            $this->simpanDokumen($pelakuUsaha, $dokumenFiles, $jenisDokumen);

            ActivityLog::catat('Tambah', 'Pelaku Usaha', "Menambahkan pelaku usaha: {$pelakuUsaha->nama_perusahaan}");

            return $pelakuUsaha;
        });
    }

    public function update(PelakuUsaha $pelakuUsaha, array $data, ?UploadedFile $foto, array $dokumenFiles = [], array $jenisDokumen = []): PelakuUsaha
    {
        return DB::transaction(function () use ($pelakuUsaha, $data, $foto, $dokumenFiles, $jenisDokumen) {
            if ($foto) {
                if ($pelakuUsaha->foto_lokasi) {
                    Storage::disk('public')->delete($pelakuUsaha->foto_lokasi);
                }
                $data['foto_lokasi'] = $foto->store('pelaku-usaha/foto-lokasi', 'public');
            }

            $pelakuUsaha->update($data);

            $this->simpanDokumen($pelakuUsaha, $dokumenFiles, $jenisDokumen);

            ActivityLog::catat('Edit', 'Pelaku Usaha', "Mengubah data pelaku usaha: {$pelakuUsaha->nama_perusahaan}");

            return $pelakuUsaha->refresh();
        });
    }

    public function delete(PelakuUsaha $pelakuUsaha): void
    {
        DB::transaction(function () use ($pelakuUsaha) {
            ActivityLog::catat('Hapus', 'Pelaku Usaha', "Menghapus pelaku usaha: {$pelakuUsaha->nama_perusahaan}");
            $pelakuUsaha->delete();
        });
    }

    protected function simpanDokumen(PelakuUsaha $pelakuUsaha, array $dokumenFiles, array $jenisDokumen): void
    {
        foreach ($dokumenFiles as $index => $file) {
            if (! $file instanceof UploadedFile) {
                continue;
            }

            $path = $file->store('pelaku-usaha/dokumen', 'public');

            PelakuUsahaDokumen::create([
                'pelaku_usaha_id' => $pelakuUsaha->id,
                'jenis_dokumen' => $jenisDokumen[$index] ?? 'Lainnya',
                'nama_file' => $file->getClientOriginalName(),
                'path_file' => $path,
                'mime_type' => $file->getClientMimeType(),
                'ukuran_file' => $file->getSize(),
                'tanggal_upload' => now(),
                'uploaded_by' => auth()->id(),
            ]);
        }
    }
}
