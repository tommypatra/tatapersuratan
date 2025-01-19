<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GrupController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\ProfilController;
use App\Http\Controllers\Api\TujuanController;
use App\Http\Controllers\Api\UploadController;
use App\Http\Controllers\Api\UserAppController;
use App\Http\Controllers\Api\UtilityController;
use App\Http\Controllers\Api\GrupUserController;
use App\Http\Controllers\Api\AksesPolaController;
use App\Http\Controllers\Api\DisposisiController;
use App\Http\Controllers\Api\PolaSuratController;
use App\Http\Controllers\Api\TtdQrcodeController;
use App\Http\Controllers\Api\DistribusiController;
use App\Http\Controllers\Api\SuratMasukController;
use App\Http\Controllers\Api\SuratKeluarController;
use App\Http\Controllers\Api\PolaSpesimenController;
use App\Http\Controllers\Api\AksesSuratMasukController;
use App\Http\Controllers\Api\SpesimenJabatanController;
use App\Http\Controllers\Api\KlasifikasiSuratController;
use App\Http\Controllers\Api\KategoriSuratMasukController;
use App\Http\Controllers\Api\LampiranSuratMasukController;
use App\Http\Controllers\Api\LampiranSuratKeluarController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('auth-login', [AuthController::class, 'login'])->name('auth-login');
Route::post('akun-baru-simpan', [AuthController::class, 'simpanPendaftaran'])->name('akun-baru-simpan');
Route::get('tte/{kode}', [UtilityController::class, 'tte']);

Route::middleware(['auth.jwt'])->group(function () {
    Route::get('cek-akses/{grup}', [UtilityController::class, 'cekAkses']);

    Route::get('get-menu/{id}', [MenuController::class, 'getMenu']);
    Route::get('info-tujuan-disposisi', [UtilityController::class, 'infoDisposisi']);
    Route::get('info-distribusi', [UtilityController::class, 'infoDistribusi']);
    Route::get('info-general', [UtilityController::class, 'infoGeneral']);
    Route::get('encode/{id}', [UtilityController::class, 'encode']);
    Route::get('encode/{id}', [UtilityController::class, 'encode']);
    Route::post('ajukan-surat-masuk', [SuratMasukController::class, 'ajukan']);
    Route::post('proses-ajuan-surat-masuk', [SuratMasukController::class, 'prosesAjuan']);
    Route::post('ajukan-surat-keluar', [SuratKeluarController::class, 'ajukan']);
    Route::post('proses-ajuan-surat-keluar', [SuratKeluarController::class, 'prosesAjuan']);
    Route::post('get-surat-masuk', [UtilityController::class, 'getSuratMasuk']);
    Route::get('get-pola-spesimen', [UtilityController::class, 'getPolaSpesimen']);
    Route::get('get-akses-pola', [UtilityController::class, 'getAksesPola']);
    Route::get('get-kategori-surat-masuk', [UtilityController::class, 'getKategoriSuratMasuk']);
    Route::get('get-users', [UtilityController::class, 'getUsers']);
    Route::get('get-pejabat', [SpesimenJabatanController::class, 'index']);
    // Route::resource('spesimen-jabatan', SpesimenJabatanController::class);

    Route::get('get-klasifikasi-surat-keluar', [UtilityController::class, 'getKlasifikasiSuratKeluar']);
    Route::post('ganti-foto-profil', [UtilityController::class, 'gantiFotoProfil']);
    Route::get('info-akun-login', [ProfilController::class, 'infoAkunLogin']);
    Route::post('ajukan-ttd-elektronik', [TtdQrcodeController::class, 'ajukan']);
    Route::post('verifikasi-ttd-elektronik', [TtdQrcodeController::class, 'verifikasi']);

    Route::middleware(['auth:api', 'cek.akses:admin'])->group(function () {
        Route::resource('user-app', UserAppController::class);
        Route::resource('grup-user', GrupUserController::class);
        Route::resource('grup', GrupController::class);
        Route::resource('akses-pola', AksesPolaController::class);
        Route::resource('akses-surat-masuk', AksesSuratMasukController::class);
        Route::resource('kategori-surat-masuk', KategoriSuratMasukController::class);
        Route::resource('spesimen-jabatan', SpesimenJabatanController::class);
        Route::resource('pola-surat-keluar', PolaSuratController::class);
        Route::resource('pola-spesimen', PolaSpesimenController::class);
        Route::resource('klasifikasi-surat-keluar', KlasifikasiSuratController::class);

        Route::get('cetak-lembar-disposisi/{id}', [SuratMasukController::class, 'show']);
    });

    Route::middleware(['auth:api', 'cek.akses:pengguna'])->group(function () {
        //user login
        // Route::middleware(['auth:sanctum', 'ability:Admin,Pengguna'])->group(function () {
        Route::resource('ttd-elektronik', TtdQrcodeController::class);
        Route::resource('distribusi', DistribusiController::class);
        Route::resource('disposisi', DisposisiController::class);
        Route::resource('tujuan', TujuanController::class);
        Route::resource('surat-masuk', SuratMasukController::class);
        Route::resource('surat-keluar', SuratKeluarController::class);
        Route::resource('lampiran-surat-masuk', LampiranSuratMasukController::class);
        Route::resource('lampiran-surat-keluar', LampiranSuratKeluarController::class);
        Route::resource('profil', ProfilController::class);
        Route::resource('upload', UploadController::class);
    });
});
