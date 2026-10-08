<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyDocument extends Model
{
    protected $fillable = [
        'company_id',
        'judul',
        'keterangan',
        'nama_file',
        'path_file'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
