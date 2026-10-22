<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyDocument extends Model
{
    protected $fillable = [
        'company_id',
        'judul',
        'keterangan',
        'tipe',
        'nama_file',
        'path_file',
        'link_drive',
        'is_read'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
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
