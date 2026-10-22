<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimelineTahapanFile extends Model
{
    protected $guarded = ['id'];

    public function tahapan()
    {
        return $this->belongsTo(TimelineTahapan::class, 'timeline_tahapan_id');
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
        return asset('storage/' . $this->file_path);
    }

    public function getIconClassAttribute(): string
    {
        if ($this->isLink()) {
            return 'fab fa-google-drive text-success';
        }

        $ext = strtolower(pathinfo($this->file_name ?? $this->file_path, PATHINFO_EXTENSION));
        return match ($ext) {
            'pdf' => 'fas fa-file-pdf text-danger',
            'doc', 'docx' => 'fas fa-file-word text-primary',
            'xls', 'xlsx' => 'fas fa-file-excel text-success',
            'ppt', 'pptx' => 'fas fa-file-powerpoint text-danger',
            'jpg', 'jpeg', 'png' => 'fas fa-file-image text-warning',
            default => 'fas fa-file-alt text-secondary',
        };
    }

    public function getFormattedSizeAttribute(): string
    {
        if ($this->isLink()) {
            return 'Google Drive';
        }
        if (!$this->file_size) {
            return '';
        }
        $bytes = $this->file_size;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 1) . ' KB';
        }
        return $bytes . ' B';
    }
}
