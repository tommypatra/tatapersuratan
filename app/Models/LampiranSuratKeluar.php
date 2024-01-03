<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LampiranSuratKeluar extends Model
{
    protected $guarded = ['id'];

    public function suratKeluar()
    {
        return $this->belongsTo(SuratKeluar::class);
    }

    public function upload()
    {
        return $this->belongsTo(Upload::class);
    }
}
