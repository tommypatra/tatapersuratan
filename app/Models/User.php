<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $guarded = ['id'];

    public function aksesPola()
    {
        return $this->hasMany(AksesPola::class);
    }

    public function disposisiSelesai()
    {
        return $this->hasMany(DisposisiSelesai::class);
    }

    public function distribusi()
    {
        return $this->hasMany(Distribusi::class);
    }


    public function disposisi()
    {
        return $this->hasMany(Disposisi::class);
    }

    public function klasifikasiSurat()
    {
        return $this->hasMany(KlasifikasiSurat::class);
    }

    public function spesimenJabatanPejabat()
    {
        return $this->hasOne(SpesimenJabatan::class, 'user_pejabat_id');
    }

    public function spesimenJabatan()
    {
        return $this->hasMany(SpesimenJabatan::class, 'user_id');
    }

    public function kategoriSuratMasuk()
    {
        return $this->hasMany(KategoriSuratMasuk::class);
    }

    public function grupUser()
    {
        return $this->hasMany(GrupUser::class);
    }

    public function upload()
    {
        return $this->hasMany(Upload::class);
    }

    public function tujuan()
    {
        return $this->hasMany(Tujuan::class, 'user_id');
    }

    // public function createdTujuan()
    // {
    //     return $this->hasMany(Tujuan::class, 'created_by');
    // }

    public function profil()
    {
        return $this->hasOne(Profil::class);
    }

    public function suratKeluar()
    {
        return $this->hasMany(SuratKeluar::class);
    }

    public function ttd()
    {
        return $this->hasMany(TtdQrcode::class, 'user_ttd_id');
    }

    public function ttdQrcode()
    {
        return $this->hasMany(TtdQrcode::class, 'user_id');
    }
}
