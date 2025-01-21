<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AksesDisposisi extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function spesimenJabatan()
    {
        return $this->belongsTo(SpesimenJabatan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
