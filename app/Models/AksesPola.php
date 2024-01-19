<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AksesPola extends Model
{
    protected $guarded = ['id'];

    public function polaSpesimen()
    {
        return $this->belongsTo(PolaSpesimen::class);
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
