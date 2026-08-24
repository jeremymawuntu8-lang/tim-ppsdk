<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaWasPrlSaksi extends Model
{
    protected $table = 'ba_was_prl_saksis';

    protected $fillable = ['ba_was_prl_id', 'nama', 'alamat', 'pekerjaan', 'tanda_tangan'];

    public function baWasPrl()
    {
        return $this->belongsTo(BaWasPrl::class);
    }
}
