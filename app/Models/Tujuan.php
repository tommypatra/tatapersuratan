<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tujuan extends Model
{
    protected $guarded = ['id'];

    public function suratMasuk()
    {
        return $this->belongsTo(SuratMasuk::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // public function createdBy()
    // {
    //     return $this->belongsTo(User::class, 'created_by');
    // }

    public function disposisi()
    {
        return $this->hasOne(Disposisi::class);
    }

    public function disposisiSelesai()
    {
        return $this->hasOne(DisposisiSelesai::class);
    }
}
