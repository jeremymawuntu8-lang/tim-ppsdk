<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'nomor_pengajuan',
        'user_id',
        'nama_perusahaan',
        'tanggal',
        'nib',
        'npwp',
        'alamat',
        'email_perusahaan',
        'nomor_telepon',
        'nama_penanggung_jawab',
        'jabatan_penanggung_jawab',
        'dokumen_diunggah',
        'file_dokumen',
        'keterangan_tambahan',
        'logo',
        'status',
        'rejection_reason',
        'catatan_admin',
        'verified_by',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggal'     => 'date',
            'verified_at' => 'datetime',
        ];
    }

    /**
     * Relasi ke user pemilik akun Google.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Admin yang memverifikasi.
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Scope: hanya perusahaan dengan status tertentu.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRevision($query)
    {
        return $query->where('status', 'revision');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isRevision(): bool
    {
        return $this->status === 'revision';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
