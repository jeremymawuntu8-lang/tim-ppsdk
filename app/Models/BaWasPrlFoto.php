<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaWasPrlFoto extends Model
{
    protected $fillable = ['ba_was_prl_id', 'path_foto', 'keterangan'];

    public function baWasPrl()
    {
        return $this->belongsTo(BaWasPrl::class);
    }
}
