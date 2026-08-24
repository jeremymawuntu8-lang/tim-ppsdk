<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = ['user_id', 'aktivitas', 'modul', 'deskripsi', 'browser', 'ip_address'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function catat(string $aktivitas, ?string $modul = null, ?string $deskripsi = null): void
    {
        static::create([
            'user_id' => auth()->id(),
            'aktivitas' => $aktivitas,
            'modul' => $modul,
            'deskripsi' => $deskripsi,
            'browser' => request()->userAgent(),
            'ip_address' => request()->ip(),
        ]);
    }
}
