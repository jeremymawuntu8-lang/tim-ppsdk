<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaWasAlseFoto extends Model
{
    protected $fillable = ['ba_was_alse_id', 'path_foto', 'keterangan'];

    public function baWasPrl()
    {
        return $this->belongsTo(BaWasAlse::class);
    }
}
