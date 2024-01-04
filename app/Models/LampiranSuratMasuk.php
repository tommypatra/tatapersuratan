<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LampiranSuratMasuk extends Model
{

    protected $guarded = ['id'];

    public function suratMasuk()
    {
        return $this->belongsTo(SuratMasuk::class);
    }

    public function upload()
    {
        return $this->belongsTo(Upload::class);
    }
}
