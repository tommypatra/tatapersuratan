<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PolaSurat extends Model
{
    protected $guarded = ['id'];

    public function aksesPola()
    {
        return $this->hasMany(AksesPola::class);
    }

    public function suratKeluar()
    {
        return $this->hasMany(SuratKeluar::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
