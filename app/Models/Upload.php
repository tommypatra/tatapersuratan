<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    protected $guarded = ['id'];

    public function lampiranSuratMasuk()
    {
        return $this->hasMany(LampiranSuratMasuk::class);
    }

    public function lampiranSuratKeluar()
    {
        return $this->hasMany(LampiranSuratKeluar::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
