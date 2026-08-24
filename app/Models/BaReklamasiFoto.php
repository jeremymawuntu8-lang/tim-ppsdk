<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaReklamasiFoto extends Model
{
    protected $table = 'ba_reklamasi_fotos';

    protected $fillable = [
        'ba_reklamasi_id', 'path_foto'
    ];

    public function baReklamasi()
    {
        return $this->belongsTo(BaReklamasi::class);
    }
}
