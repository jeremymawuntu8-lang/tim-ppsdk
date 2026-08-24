<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaPencemaranPengawas extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function baPencemaran()
    {
        return $this->belongsTo(BaPencemaran::class);
    }
}
