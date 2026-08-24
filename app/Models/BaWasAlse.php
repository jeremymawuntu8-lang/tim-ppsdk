<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaWasAlse extends Model
{
    use SoftDeletes;

    protected $table = 'ba_was_alses';

    protected $fillable = [
        'nomor_ba', 'pelaku_usaha_id', 'provinsi_id', 'nomor_pkkprl', 'tanggal_pengawasan',
        'tim_pengawas', 'lokasi', 'hasil_pengawasan', 'kesimpulan', 'rekomendasi',
        'status', 'file_ba_pdf', 'latitude', 'longitude', 'created_by',
        'no_surat_tugas', 'unit_kerja', 'ketua_tim_nama', 'ketua_tim_nip', 'ketua_tim_jabatan', 'ketua_tim_unit_kerja',
        'jam_wita', 'nama_usaha', 'titik_koordinat',
        'kategori_pengawasan', 'objek_pengawasan', 'no_identitas', 'alamat_kantor', 'alamat_kegiatan',
        'nomor_nib', 'jenis_kegiatan_usaha', 'penerbit_izin', 'nomor_izin_alse', 'tgl_terbit_izin_alse',
        'masa_berlaku_izin_alse', 'nama_dokumen_lain', 'nomor_dokumen_lain', 'kategori_kawasan',
        'judul_pemenuhan_ketentuan', 'debit_volume_air_laut', 'kesesuaian_volume_air', 'kesesuaian_koordinat_inlet',
        'dugaan_pelanggaran', 'penjelasan_dugaan_pelanggaran', 'analisa_pengawasan',
        'metode_pengamatan', 'nomor_perda_rzwp3k', 'tgl_terbit_pkkprl',
        'status_kesesuaian_kkprl', 'catatan_dokumen_pkkprl', 'pemenuhan_kewajiban_pkkprl',
        'penanggung_jawab_usaha', 'jabatan_pj_usaha',
        'catatan_pengesahan', 'ketua_tim_tanda_tangan', 'pj_usaha_tanda_tangan',
    ];

    protected $casts = [
        'tanggal_pengawasan' => 'date',
        'tgl_terbit_pkkprl' => 'date',
        'tgl_terbit_izin_alse' => 'date',
    ];

    public function pelakuUsaha()
    {
        return $this->belongsTo(PelakuUsaha::class);
    }

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class);
    }

    public function getNamaPelakuUsahaCetakAttribute(): ?string
    {
        return $this->nama_usaha ?: ($this->pelakuUsaha?->nama_perusahaan ?? '-');
    }

    public function getAlamatKantorCetakAttribute(): ?string
    {
        return $this->alamat_kantor ?: ($this->pelakuUsaha?->alamat_utama ?? '-');
    }

    public function getProvinsiCetakAttribute(): ?string
    {
        return $this->provinsi?->nama ?: ($this->pelakuUsaha?->provinsi?->nama ?? '-');
    }

    public function fotos()
    {
        return $this->hasMany(BaWasAlseFoto::class);
    }

    public function pengawas()
    {
        return $this->hasMany(BaWasAlsePengawas::class);
    }

    public function saksis()
    {
        return $this->hasMany(BaWasAlseSaksi::class);
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
