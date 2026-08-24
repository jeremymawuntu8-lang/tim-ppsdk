<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaPencemaran extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_pengawasan' => 'date',
        'laporan_pengaduan_tgl' => 'date',
        'sampel_tgl' => 'date',
        'sampel_lab_tgl' => 'date',
        'jenis_usaha' => 'array',
        'perizinan_dasar' => 'array',
        'dokumen_pencegahan' => 'array',
        'hasil_pengawasan' => 'array',
        'indikasi_ketidakpatuhan' => 'array',
        'lampiran_e1' => 'array',
        'lampiran_e2' => 'array',
        'lampiran_e3' => 'array',
        'lampiran_e4' => 'array',
        'lampiran_e5' => 'array',
        'lampiran_e6' => 'array',
        'perizinan_berusaha' => 'array',
    ];

    public function pelakuUsaha()
    {
        return $this->belongsTo(PelakuUsaha::class);
    }

    public function pengawas()
    {
        return $this->hasMany(BaPencemaranPengawas::class);
    }

    public function fotos()
    {
        return $this->hasMany(BaPencemaranFoto::class);
    }

    public function scopeFilter($query, $filters)
    {
        $search = $filters['search'] ?? null;
        if (is_array($search)) {
            $search = $search['value'] ?? null;
        }

        $query->when($search, function($q, $search) {
            $q->where('nomor_ba', 'like', "%{$search}%")
              ->orWhereHas('pelakuUsaha', function($q) use ($search) {
                  $q->where('nama_perusahaan', 'like', "%{$search}%");
              })
              ->orWhere('nama_pj', 'like', "%{$search}%");
        });
    }
}
