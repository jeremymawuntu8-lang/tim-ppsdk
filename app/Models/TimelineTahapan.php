<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimelineTahapan extends Model
{
    protected $guarded = ['id'];

    public function pelakuUsaha()
    {
        return $this->belongsTo(PelakuUsaha::class);
    }

    public function files()
    {
        return $this->hasMany(TimelineTahapanFile::class);
    }
}
