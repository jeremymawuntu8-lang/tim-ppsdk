<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPengawasan extends Model
{
    protected $fillable = [
        'pelaku_usaha_id', 'jenis_pengawasan', 'tanggal_rencana',
        'tim_pengawas', 'status', 'catatan', 'created_by',
    ];

    protected $casts = ['tanggal_rencana' => 'date'];

    public function pelakuUsaha()
    {
        return $this->belongsTo(PelakuUsaha::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
