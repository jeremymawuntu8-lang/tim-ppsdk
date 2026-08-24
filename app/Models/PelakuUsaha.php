<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PelakuUsaha extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nama_perusahaan', 'nomor_pkkprl', 'jenis_usaha_id', 'luas_pkkprl',
        'provinsi_id', 'kabupaten_id', 'kecamatan_id', 'kelurahan_id',
        'alamat', 'latitude', 'longitude', 'nama_pic', 'jabatan_pic',
        'nomor_hp', 'email', 'status', 'foto_lokasi', 'created_by',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'luas_pkkprl' => 'decimal:2',
    ];

    public function jenisUsaha()
    {
        return $this->belongsTo(JenisUsaha::class);
    }

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class);
    }

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class);
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class);
    }

    public function dokumens()
    {
        return $this->hasMany(PelakuUsahaDokumen::class);
    }

    public function dokumenPelakuUsahas()
    {
        return $this->hasMany(DokumenPelakuUsaha::class);
    }

    public function baWasPrls()
    {
        return $this->hasMany(BaWasPrl::class);
    }

    public function baWasAlses()
    {
        return $this->hasMany(BaWasAlse::class);
    }

    public function baReklamasis()
    {
        return $this->hasMany(BaReklamasi::class);
    }

    public function baPpks()
    {
        return $this->hasMany(BaPpk::class);
    }

    public function baPencemarans()
    {
        return $this->hasMany(BaPencemaran::class);
    }

    public function jadwalPengawasans()
    {
        return $this->hasMany(JadwalPengawasan::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeFilter($query, array $filters)
    {
        $search = is_array($filters['search'] ?? null) ? ($filters['search']['value'] ?? null) : ($filters['search'] ?? null);

        return $query
            ->when($search, fn ($q, $v) => $q->where(function ($q2) use ($v) {
                $q2->where('nama_perusahaan', 'like', "%{$v}%")
                    ->orWhere('nomor_pkkprl', 'like', "%{$v}%");
            }))
            ->when($filters['provinsi_id'] ?? null, fn ($q, $v) => $q->where('provinsi_id', $v))
            ->when($filters['kabupaten_id'] ?? null, fn ($q, $v) => $q->where('kabupaten_id', $v))
            ->when($filters['jenis_usaha_id'] ?? null, fn ($q, $v) => $q->where('jenis_usaha_id', $v))
            ->when($filters['status'] ?? null, fn ($q, $v) => $q->where('status', $v));
    }
}
