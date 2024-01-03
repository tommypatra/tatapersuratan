<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpesimenJabatan extends Model
{
    protected $guarded = ['id'];

    public function suratKeluar()
    {
        return $this->hasMany(SuratKeluar::class);
    }

    public function aksesPola()
    {
        return $this->hasMany(AksesPola::class);
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
