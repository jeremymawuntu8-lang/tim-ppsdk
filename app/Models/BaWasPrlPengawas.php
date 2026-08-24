<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaWasPrlPengawas extends Model
{
    protected $table = 'ba_was_prl_pengawas';

    protected $fillable = ['ba_was_prl_id', 'nama', 'nip', 'jabatan', 'unit_kerja', 'tanda_tangan'];

    public function baWasPrl()
    {
        return $this->belongsTo(BaWasPrl::class);
    }
}
