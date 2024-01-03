<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriSuratMasuk extends Model
{
    protected $guarded = ['id'];

    public function suratMasuk()
    {
        return $this->hasMany(SuratMasuk::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
