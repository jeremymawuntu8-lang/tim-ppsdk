<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PengawasanTidakLangsung extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pengawasan_tidak_langsung';

    protected $fillable = [
        'nomor',
        'nama_unit_kerja',
        'lokasi',
        'pelaku_usaha_id',
        'kegiatan_usaha',
        'pelapor_sumber',
        'tanggal_laporan',
        'tanggal_telaah',
        'hasil_telaah_penjelasan',
        'rekomendasi',
        'kepala_upt_nama',
        'kepala_upt_ttd',
        'status',
        'created_by'
    ];

    protected $casts = [
        'tanggal_laporan' => 'date',
        'tanggal_telaah' => 'date',
    ];

    public function pelakuUsaha()
    {
        return $this->belongsTo(PelakuUsaha::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function gambars()
    {
        return $this->hasMany(PengawasanTidakLangsungGambar::class, 'pengawasan_tidak_langsung_id');
    }

    public function kesesuaian()
    {
        return $this->hasMany(PengawasanTidakLangsungKesesuaian::class, 'pengawasan_tidak_langsung_id');
    }

    public function scopeFilter($query, array $filters)
    {
        $search = is_array($filters['search'] ?? null) ? ($filters['search']['value'] ?? null) : ($filters['search'] ?? null);

        return $query
            ->when($search, fn ($q, $v) => $q->where('nomor', 'like', "%{$v}%"))
            ->when($filters['status'] ?? null, fn ($q, $v) => $q->where('status', $v))
            ->when($filters['dari_tanggal'] ?? null, fn ($q, $v) => $q->whereDate('tanggal_laporan', '>=', $v))
            ->when($filters['sampai_tanggal'] ?? null, fn ($q, $v) => $q->whereDate('tanggal_laporan', '<=', $v));
    }
}
