<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $guard_name = 'web';

    protected $fillable = [
        'name', 'email', 'password', 'no_hp', 'jabatan', 'foto_profil', 'is_active',
        'google_id', 'auth_provider',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    /**
     * Cek apakah user adalah perusahaan (login via Google).
     */
    public function isCompany(): bool
    {
        return $this->auth_provider === 'google';
    }

    public function pelakuUsahasDibuat()
    {
        return $this->hasMany(PelakuUsaha::class, 'created_by');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Relasi ke profil perusahaan (hanya untuk user google).
     */
    public function company()
    {
        return $this->hasOne(Company::class);
    }
}
