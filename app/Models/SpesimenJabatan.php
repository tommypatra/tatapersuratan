<?php

namespace App\Models;

use App\Models\AksesPola;
use App\Models\SuratKeluar;
use App\Models\PolaSpesimen;
use Illuminate\Database\Eloquent\Model;

class SpesimenJabatan extends Model
{
    protected $guarded = ['id'];

    public function aksesSuratMasuk()
    {
        return $this->hasMany(AksesSuratMasuk::class);
    }

    public function polaSpesimen()
    {
        return $this->hasMany(PolaSpesimen::class);
    }

    public function suratKeluar()
    {
        return $this->hasMany(SuratKeluar::class);
    }

    public function pejabat()
    {
        return $this->belongsTo(User::class, 'user_pejabat_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
