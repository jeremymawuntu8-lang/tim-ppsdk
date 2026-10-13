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

    public function getFormattedSizeAttribute(): string
    {
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
