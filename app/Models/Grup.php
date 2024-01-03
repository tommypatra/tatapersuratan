<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grup extends Model
{
    protected $guarded = ['id'];

    public function grupUser()
    {
        return $this->hasMany(GrupUser::class);
    }
}
