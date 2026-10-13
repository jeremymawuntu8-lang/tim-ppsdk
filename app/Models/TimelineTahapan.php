<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimelineTahapan extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function pelakuUsaha()
    {
        return $this->belongsTo(PelakuUsaha::class);
    }

    public function files()
    {
        return $this->hasMany(TimelineTahapanFile::class);
    }

    public function bahanPaparan()
    {
        return $this->hasOne(TimelineTahapanFile::class)->where('kategori', 'bahan_paparan');
    }

    public function permohonanSupervisi()
    {
        return $this->hasOne(TimelineTahapanFile::class)->where('kategori', 'permohonan_supervisi');
    }

    public function petaSpasials()
    {
        return $this->hasMany(TimelineTahapanFile::class)->where('kategori', 'peta_spasial')->orderBy('slot');
    }

    public function dokumenTambahans()
    {
        return $this->hasMany(TimelineTahapanFile::class)->where('kategori', 'dokumen_tambahan')->latest();
    }
}
