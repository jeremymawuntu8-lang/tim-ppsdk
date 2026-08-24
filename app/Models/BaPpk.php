<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaPpk extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_pengawasan' => 'date',
        'rek_ppk_tgl' => 'date',
        'pkkpr_tgl' => 'date',
        'lingkungan_tgl' => 'date',
        'nib_tgl' => 'date',
        'izin_usaha_tgl' => 'date',
        'dok_lain_tgl' => 'date',
        'jenis_usaha' => 'array',
        'rekomendasi_tindakan' => 'array',
    ];

    public function pelakuUsaha()
    {
        return $this->belongsTo(PelakuUsaha::class);
    }

    public function pengawas()
    {
        return $this->hasMany(BaPpkPengawas::class);
    }

    public function fotos()
    {
        return $this->hasMany(BaPpkFoto::class);
    }

    public function scopeFilter($query, $filters)
    {
        $search = $filters['search'] ?? null;
        // DataTables sends search as array: search[value], search[regex]
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
