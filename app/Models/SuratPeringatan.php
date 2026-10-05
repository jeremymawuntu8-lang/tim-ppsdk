<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratPeringatan extends Model
{
    use HasFactory;
    
    protected $table = 'surat_peringatans';

    protected $fillable = [
        'id_penerbitan',
        'pelaku_usaha_id',
        'contact_person',
        'alamat',
        'jenis_permohonan',
        'status_berusaha',
        'provinsi',
        'nama_perairan',
        'upt',
        'detil_kegiatan',
        'luas',
        'panjang',
        'nomor_kkprl',
        'tanggal_penerbitan',
        'laporan_1_tgl',
        'laporan_1_status',
        'laporan_2_tgl',
        'laporan_2_status',
        'laporan_3_tgl',
        'laporan_3_status',
        'laporan_4_tgl',
        'laporan_4_status',
        'laporan_5_tgl',
        'laporan_5_status',
        'sanksi',
        'sp1_keterangan',
        'sp1_nomor_surat_tgl',
        'sp1_status_terkirim',
        'sp1_link_upload',
        'sp2_keterangan',
        'sp2_nomor_surat_tgl',
        'sp2_status_terkirim',
        'sp2_link_upload',
        'sp3_keterangan',
        'sp3_nomor_surat_tgl',
        'sp3_status_terkirim',
        'sp3_link_upload',
        'pemutakhiran',
        'link_dokumen_laporan_tahunan',
        'link_dokumen_kkprl',
        'created_by'
    ];

    protected $casts = [
        'tanggal_penerbitan' => 'date',
        'laporan_1_tgl' => 'date',
        'laporan_2_tgl' => 'date',
        'laporan_3_tgl' => 'date',
        'laporan_4_tgl' => 'date',
        'laporan_5_tgl' => 'date',
    ];

    public function pelakuUsaha()
    {
        return $this->belongsTo(PelakuUsaha::class);
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi polymorphic ke arsip_dokumen_ba.
     */
    public function arsipDokumen()
    {
        return $this->morphMany(ArsipDokumenBa::class, 'arsipable');
    }
    
    /**
     * Alias for BA generic components
     */
    public function getNomorBaAttribute()
    {
        return $this->id_penerbitan;
    }
}
