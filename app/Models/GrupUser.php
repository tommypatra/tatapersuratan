<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrupUser extends Model
{
    protected $guarded = ['id'];

    public function grup()
    {
        return $this->belongsTo(Grup::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
