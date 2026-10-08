<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArsipDokumenBa extends Model
{
    protected $table = 'arsip_dokumen_ba';

    protected $fillable = [
        'arsipable_type',
        'arsipable_id',
        'tipe',
        'judul',
        'keterangan',
        'path_file',
        'nama_file',
        'link_gdrive',
        'uploaded_by',
    ];

    /**
     * Relasi polymorphic ke BA (BaWasPrl, BaWasAlse, BaReklamasi, BaPpk, BaPencemaran).
     */
    public function arsipable()
    {
        return $this->morphTo();
    }

    /**
     * User yang mengunggah arsip.
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Cek apakah arsip ini berupa file upload.
     */
    public function isFile(): bool
    {
        return $this->tipe === 'file';
    }

    /**
     * Cek apakah arsip ini berupa link Google Drive.
     */
    public function isLink(): bool
    {
        return $this->tipe === 'link';
    }

    /**
     * Mendapatkan ekstensi file.
     */
    public function getEkstensiAttribute(): string
    {
        if (!$this->nama_file) {
            return '';
        }
        return strtolower(pathinfo($this->nama_file, PATHINFO_EXTENSION));
    }

    /**
     * Icon FontAwesome berdasarkan tipe dan ekstensi file.
     */
    public function getIconClassAttribute(): string
    {
        if ($this->isLink()) {
            return 'fab fa-google-drive text-success';
        }

        return match ($this->ekstensi) {
            'pdf' => 'fas fa-file-pdf text-danger',
            'doc', 'docx' => 'fas fa-file-word text-primary',
            'xls', 'xlsx' => 'fas fa-file-excel text-success',
            'jpg', 'jpeg', 'png' => 'fas fa-file-image text-warning',
            default => 'fas fa-file-alt text-secondary',
        };
    }

    /**
     * Ukuran file yang diformat jika file ada di storage.
     */
    public function getUkuranFormattedAttribute(): ?string
    {
        if (!$this->isFile() || !$this->path_file) {
            return null;
        }

        try {
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($this->path_file)) {
                $bytes = \Illuminate\Support\Facades\Storage::disk('public')->size($this->path_file);
                if ($bytes >= 1048576) {
                    return number_format($bytes / 1048576, 1) . ' MB';
                }
                return number_format($bytes / 1024, 0) . ' KB';
            }
        } catch (\Throwable $e) {
            // ignore
        }

        return null;
    }

    /**
     * Nama label jenis BA (PRL, ALSE, Reklamasi, PPK, Pencemaran).
     */
    public function getNamaTipeBaAttribute(): string
    {
        if (!$this->arsipable_type) {
            return 'Arsip Tanpa ID';
        }

        return match ($this->arsipable_type) {
            BaWasPrl::class => 'BA WAS PRL',
            BaWasAlse::class => 'BA WAS ALSE',
            BaReklamasi::class => 'BA Reklamasi',
            BaPpk::class => 'BA PPK',
            BaPencemaran::class => 'BA Pencemaran',
            SuratPeringatan::class => 'Surat Peringatan',
            default => class_basename($this->arsipable_type),
        };
    }

    /**
     * URL ke halaman detail BA terkait.
     */
    public function getRouteShowAttribute(): ?string
    {
        if (!$this->arsipable_id) {
            return null;
        }

        return match ($this->arsipable_type) {
            BaWasPrl::class => route('ba-was-prl.show', $this->arsipable_id),
            BaWasAlse::class => route('ba-was-alse.show', $this->arsipable_id),
            BaReklamasi::class => route('ba-reklamasi.show', $this->arsipable_id),
            BaPpk::class => route('ba-ppk.show', $this->arsipable_id),
            BaPencemaran::class => route('ba-pencemaran.show', $this->arsipable_id),
            SuratPeringatan::class => route('surat-peringatan.show', $this->arsipable_id),
            default => null,
        };
    }
}
