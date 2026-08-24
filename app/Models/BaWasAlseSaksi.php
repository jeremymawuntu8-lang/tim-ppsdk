<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaWasAlseSaksi extends Model
{
    protected $table = 'ba_was_alse_saksis';

    protected $fillable = ['ba_was_alse_id', 'nama', 'alamat', 'pekerjaan', 'tanda_tangan'];

    public function baWasAlse()
    {
        return $this->belongsTo(BaWasAlse::class);
    }
}
