<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengawasanTidakLangsungGambar extends Model
{
    use HasFactory;

    protected $table = 'pengawasan_tidak_langsung_gambars';

    protected $fillable = [
        'pengawasan_tidak_langsung_id',
        'kategori',
        'path_file'
    ];

    public function pengawasanTidakLangsung()
    {
        return $this->belongsTo(PengawasanTidakLangsung::class, 'pengawasan_tidak_langsung_id');
    }
}
