<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengawasanTidakLangsungKesesuaian extends Model
{
    use HasFactory;

    protected $table = 'pengawasan_tidak_langsung_kesesuaian';

    protected $fillable = [
        'pengawasan_tidak_langsung_id',
        'alokasi_ruang',
        'kegiatan',
        'kesesuaian',
        'keterangan'
    ];

    public function pengawasanTidakLangsung()
    {
        return $this->belongsTo(PengawasanTidakLangsung::class, 'pengawasan_tidak_langsung_id');
    }
}
