<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimelineTahapanFile extends Model
{
    protected $guarded = ['id'];

    public function tahapan()
    {
        return $this->belongsTo(TimelineTahapan::class, 'timeline_tahapan_id');
    }
}
