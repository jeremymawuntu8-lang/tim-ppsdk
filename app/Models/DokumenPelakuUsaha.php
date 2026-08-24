<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenPelakuUsaha extends Model
{
    protected $fillable = [
        'pelaku_usaha_id', 'nama_pic', 'jabatan', 'nomor_hp', 'email',
        'jenis_dokumen', 'nama_file', 'path_file', 'tanggal_upload', 'uploaded_by',
    ];

    protected $casts = ['tanggal_upload' => 'date'];

    public function pelakuUsaha()
    {
        return $this->belongsTo(PelakuUsaha::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
