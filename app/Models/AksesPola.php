<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AksesPola extends Model
{
    protected $guarded = ['id'];

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
        return $this->hasMany(SuratKeluar::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
