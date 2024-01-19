<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    protected $guarded = ['id'];

    public function lampiranSuratKeluar()
    {
        return $this->hasMany(LampiranSuratKeluar::class);
    }

    public function distribusi()
    {
        return $this->hasMany(Distribusi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function polaSurat()
    {
        return $this->belongsTo(PolaSurat::class);
    }

    public function spesimenJabatan()
    {
        return $this->belongsTo(SpesimenJabatan::class);
    }

    public function polaSpesimen()
    {
        return $this->belongsTo(PolaSpesimen::class);
    }


    public function klasifikasiSurat()
    {
        return $this->belongsTo(KlasifikasiSurat::class);
    }
}
