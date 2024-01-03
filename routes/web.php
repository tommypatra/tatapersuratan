<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [WebController::class, 'masuk'])->name('akun-masuk')->middleware('guest');
Route::get('/akun-daftar', [WebController::class, 'mendaftar'])->name('akun-daftar')->middleware('guest');

Route::post('/akun-set-session', [WebController::class, 'setSession'])->name('akun-set-session');
Route::get('/tte/{kode}', [WebController::class, 'detailTte'])->name('tte');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/akun-daftar-akses', [WebController::class, 'daftarAkses'])->name('akun-daftar-akses');
    Route::get('/akun-set-akses/{grup_id}', [WebController::class, 'setAkses'])->name('akun-set-akses');
    Route::get('/akun-dashboard', [WebController::class, 'dashboard'])->name('akun-dashboard');
    Route::get('/akun-keluar', [AuthController::class, 'logout'])->name('akun-keluar');

    Route::get('/surat-masuk', [WebController::class, 'suratMasuk'])->name('surat-masuk');
    Route::get('/kategori-surat-masuk', [WebController::class, 'kategoriSuratMasuk'])->name('kategori-surat-masuk');
    Route::get('/user-app', [WebController::class, 'userApp'])->name('user-app');
    Route::get('/pola-surat-keluar', [WebController::class, 'polaSuratKeluar'])->name('pola-surat-keluar');
    Route::get('/klasifikasi-surat-keluar', [WebController::class, 'klasifikasiSuratKeluar'])->name('klasifikasi-surat-keluar');
    Route::get('/grup', [WebController::class, 'grup'])->name('grup');
    Route::get('/spesimen-jabatan', [WebController::class, 'spesimenJabatan'])->name('spesimen-jabatan');
    Route::get('/akses-pola', [WebController::class, 'aksesPola'])->name('akses-pola');
    Route::get('/profil', [WebController::class, 'profil'])->name('profil');
    Route::get('/disposisi', [WebController::class, 'disposisi'])->name('disposisi');
    Route::get('/distribusi', [WebController::class, 'distribusi'])->name('distribusi');
    Route::get('/ttd-elektronik', [WebController::class, 'ttdElektronik'])->name('ttd-elektronik');
    Route::get('/disposisi-detail/{id}', [WebController::class, 'disposisiDetail'])->name('disposisi-baca');
    Route::get('/surat-keluar', [WebController::class, 'suratKeluar'])->name('surat-keluar');
    Route::get('/surat-keluar-detail/{id}', [WebController::class, 'suratKeluarDetail'])->name('surat-keluar-detail');
});
