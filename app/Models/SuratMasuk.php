<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    protected $guarded = ['id'];

    public function lampiranSuratMasuk()
    {
        return $this->hasMany(LampiranSuratMasuk::class);
    }

    public function tujuan()
    {
        return $this->hasMany(Tujuan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pejabat()
    {
        return $this->belongsTo(User::class, 'user_pejabat_id');
    }

    public function kategoriSuratMasuk()
    {
        return $this->belongsTo(KategoriSuratMasuk::class);
    }
}
