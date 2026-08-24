<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PelakuUsahaDokumen extends Model
{
    protected $fillable = [
        'pelaku_usaha_id', 'jenis_dokumen', 'nama_file', 'path_file',
        'mime_type', 'ukuran_file', 'tanggal_upload', 'uploaded_by',
    ];

    public function pelakuUsaha()
    {
        return $this->belongsTo(PelakuUsaha::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
