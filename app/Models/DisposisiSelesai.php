<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisposisiSelesai extends Model
{
    protected $guarded = ['id'];

    public function tujuan()
    {
        return $this->belongsTo(Tujuan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
