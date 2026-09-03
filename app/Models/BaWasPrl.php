<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaWasPrl extends Model
{
    use SoftDeletes;

    protected $table = 'ba_was_prls';

    protected $fillable = [
        'nomor_ba', 'pelaku_usaha_id', 'nomor_pkkprl', 'tanggal_pengawasan',
        'tim_pengawas', 'lokasi', 'hasil_pengawasan', 'kesimpulan', 'rekomendasi',
        'status', 'file_ba_pdf', 'latitude', 'longitude', 'created_by',
        // Field baru — Pengawas yang Bertugas
        'no_surat_tugas', 'ketua_tim_nama', 'ketua_tim_nip', 'ketua_tim_jabatan', 'ketua_tim_unit_kerja',
        // Field baru — Informasi Pengawasan
        'jam_wita', 'nama_usaha', 'titik_koordinat', 'titik_koordinat_existing',
        // Field baru — Detail Pelaku Usaha (snapshot, override dari relasi PelakuUsaha)
        'jenis_usaha', 'kbli', 'luas_area', 'provinsi_id',
        // Field baru — Form Pengawasan
        'metode_pengamatan', 'nomor_perda_rzwp3k', 'tgl_terbit_pkkprl',
        'status_kesesuaian_kkprl', 'catatan_dokumen_pkkprl', 'pemenuhan_kewajiban_pkkprl',
        // Field baru — Detail KKPRL & Izin Pengelolaan
        'kkprl_instansi_penerbit', 'kkprl_masa_berlaku',
        'izin_pengelolaan_nomor', 'izin_pengelolaan_instansi_penerbit',
        'izin_pengelolaan_tanggal_penerbitan', 'izin_pengelolaan_masa_berlaku',
        'kesesuaian_izin_pengelolaan',
        // Field baru — Formulir Pemenuhan Dokumen KKPRL
        'penyampaian_laporan_tertulis', 'catatan_laporan_tahunan',
        'dampak_pelaksanaan_pkkprl', 'catatan_dampak_prl',
        // Field baru — Informasi Pelaku Usaha
        'penanggung_jawab_usaha', 'jabatan_pj_usaha',
        // Field baru — Pengesahan (tanda tangan)
        'catatan_pengesahan', 'ketua_tim_tanda_tangan', 'pj_usaha_tanda_tangan',
    ];

    protected $casts = [
        'tanggal_pengawasan' => 'date',
        'tgl_terbit_pkkprl' => 'date',
        'izin_pengelolaan_tanggal_penerbitan' => 'date',
    ];

    public function pelakuUsaha()
    {
        return $this->belongsTo(PelakuUsaha::class);
    }

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class);
    }

    /**
     * Nama jenis usaha untuk dicetak: pakai snapshot jika diisi,
     * kalau tidak fallback ke relasi jenis usaha milik Pelaku Usaha.
     */
    public function getJenisUsahaCetakAttribute(): ?string
    {
        return $this->jenis_usaha ?: $this->pelakuUsaha?->jenisUsaha?->nama;
    }

    public function getLuasAreaCetakAttribute(): ?string
    {
        return $this->luas_area ?: ($this->pelakuUsaha?->luas_pkkprl ? (string) $this->pelakuUsaha->luas_pkkprl : null);
    }

    public function getProvinsiCetakAttribute(): ?string
    {
        return $this->provinsi?->nama ?: $this->pelakuUsaha?->provinsi?->nama;
    }

    public function getNamaUsahaCetakAttribute(): ?string
    {
        return $this->nama_usaha ?: $this->pelakuUsaha?->nama_perusahaan;
    }

    public function fotos()
    {
        return $this->hasMany(BaWasPrlFoto::class);
    }

    public function pengawas()
    {
        return $this->hasMany(BaWasPrlPengawas::class);
    }

    public function saksis()
    {
        return $this->hasMany(BaWasPrlSaksi::class);
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
