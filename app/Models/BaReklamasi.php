<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaReklamasi extends Model
{
    use SoftDeletes;

    protected $table = 'ba_reklamasis';

    protected $fillable = [
        'nomor_ba', 'pelaku_usaha_id', 'tanggal_pengawasan', 'jam_wita', 'status', 'file_ba_pdf',
        'penanggung_jawab_usaha', 'nik_pj', 'alamat_pj', 'pelaksana_reklamasi', 'lokasi_reklamasi', 'jenis_pemanfaatan_reklamasi',
        'kkprl_nomor_izin', 'kkprl_terbit_izin', 'kkprl_pemberi_izin', 'kkprl_peruntukan',
        'izin_reklamasi_nomor', 'izin_reklamasi_terbit', 'izin_reklamasi_pemberi', 'izin_reklamasi_peruntukan',
        'izin_lainnya_nomor', 'izin_lainnya_terbit', 'izin_lainnya_pemberi', 'izin_lainnya_peruntukan',
        'latitude', 'longitude', 'created_by',
        'ttd_pelaku_usaha', 'ttd_pengawas_1', 'ttd_pengawas_2'
    ];

    protected $casts = [
        'tanggal_pengawasan' => 'date',
        'kkprl_terbit_izin' => 'date',
        'izin_reklamasi_terbit' => 'date',
        'izin_lainnya_terbit' => 'date',
    ];

    public function pelakuUsaha()
    {
        return $this->belongsTo(PelakuUsaha::class);
    }

    public function pengawas()
    {
        return $this->hasMany(BaReklamasiPengawas::class);
    }

    public function fotos()
    {
        return $this->hasMany(BaReklamasiFoto::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeFilter($query, array $filters)
    {
        $search = is_array($filters['search'] ?? null) ? ($filters['search']['value'] ?? null) : ($filters['search'] ?? null);

        return $query
            ->when($search, fn ($q, $v) => $q->where('nomor_ba', 'like', "%{$v}%"))
            ->when($filters['status'] ?? null, fn ($q, $v) => $q->where('status', $v))
            ->when($filters['dari_tanggal'] ?? null, fn ($q, $v) => $q->whereDate('tanggal_pengawasan', '>=', $v))
            ->when($filters['sampai_tanggal'] ?? null, fn ($q, $v) => $q->whereDate('tanggal_pengawasan', '<=', $v));
    }
}
