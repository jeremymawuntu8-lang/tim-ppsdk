<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenPelakuUsaha extends Model
{
    protected $fillable = [
        'pelaku_usaha_id', 'nama_pic', 'jabatan', 'nomor_hp', 'email',
        'jenis_dokumen', 'tipe', 'nama_file', 'path_file', 'link_drive',
        'tanggal_upload', 'uploaded_by',
    ];

    protected $casts = ['tanggal_upload' => 'date'];

    public function pelakuUsaha()
    {
        return $this->belongsTo(PelakuUsaha::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function isLink(): bool
    {
        return $this->tipe === 'link' || !empty($this->link_drive);
    }

    public function isFile(): bool
    {
        return !$this->isLink();
    }

    public function getUrlAttribute(): string
    {
        if ($this->isLink()) {
            return $this->link_drive ?: '#';
        }
        return asset('storage/' . $this->path_file);
    }

    public function getIconClassAttribute(): string
    {
        if ($this->isLink()) {
            return 'fab fa-google-drive text-success';
        }

        $ext = strtolower(pathinfo($this->nama_file ?? $this->path_file, PATHINFO_EXTENSION));
        return match ($ext) {
            'pdf' => 'fas fa-file-pdf text-danger',
            'doc', 'docx' => 'fas fa-file-word text-primary',
            'xls', 'xlsx' => 'fas fa-file-excel text-success',
            'jpg', 'jpeg', 'png' => 'fas fa-file-image text-warning',
            default => 'fas fa-file-alt text-secondary',
        };
    }
}
