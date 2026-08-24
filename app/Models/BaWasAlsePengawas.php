<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaWasAlsePengawas extends Model
{
    protected $table = 'ba_was_alse_pengawas';

    protected $fillable = ['ba_was_alse_id', 'nama', 'nip', 'jabatan', 'unit_kerja', 'tanda_tangan'];

    public function baWasAlse()
    {
        return $this->belongsTo(BaWasAlse::class);
    }
}
