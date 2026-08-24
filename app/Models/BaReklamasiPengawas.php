<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaReklamasiPengawas extends Model
{
    protected $table = 'ba_reklamasi_pengawas';

    protected $fillable = [
        'ba_reklamasi_id', 'nama', 'nip', 'jabatan', 'unit_kerja', 'tanda_tangan'
    ];

    public function baReklamasi()
    {
        return $this->belongsTo(BaReklamasi::class);
    }
}
