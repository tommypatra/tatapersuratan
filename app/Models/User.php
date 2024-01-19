<?php

namespace App\Models;

use App\Models\Profil;
use App\Models\Tujuan;
use App\Models\Upload;
use App\Models\GrupUser;
use App\Models\AksesPola;
use App\Models\Disposisi;
use App\Models\TtdQrcode;
use App\Models\Distribusi;
use App\Models\SuratKeluar;
use App\Models\PolaSpesimen;
use App\Models\SpesimenJabatan;
use App\Models\DisposisiSelesai;
use App\Models\KlasifikasiSurat;
use Laravel\Sanctum\HasApiTokens;
use App\Models\KategoriSuratMasuk;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $guarded = ['id'];

    public function polaSpesimen()
    {
        return $this->hasMany(PolaSpesimen::class);
    }

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
