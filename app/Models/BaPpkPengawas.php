<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaPpkPengawas extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];

    public function baPpk()
    {
        return $this->belongsTo(BaPpk::class);
    }
}
