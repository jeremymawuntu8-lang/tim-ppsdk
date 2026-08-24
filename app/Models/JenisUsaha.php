<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisUsaha extends Model
{
    use SoftDeletes;

    protected $fillable = ['nama', 'kode', 'keterangan'];

    public function pelakuUsahas()
    {
        return $this->hasMany(PelakuUsaha::class);
    }
}
