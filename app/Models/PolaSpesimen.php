<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolaSpesimen extends Model
{
    protected $guarded = ['id'];

    // public function aksesPola()
    // {
    //     return $this->hasMany(AksesPola::class);
    // }

    public function polaSurat()
    {
        return $this->belongsTo(PolaSurat::class);
    }

    public function spesimenJabatan()
    {
        return $this->belongsTo(SpesimenJabatan::class);
    }

    public function suratKeluar()
    {
        return $this->hasMany(suratKeluar::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
