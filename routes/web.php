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

Route::get('auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/callback', [AuthController::class, 'handleGoogleCallback']);

Route::get('/', [WebController::class, 'masuk'])->name('akun-masuk')->middleware('guest');
Route::get('/login', [WebController::class, 'masuk'])->name('login')->middleware('guest');
Route::get('/akun-daftar', [WebController::class, 'mendaftar'])->name('akun-daftar')->middleware('guest');

Route::post('/akun-set-session', [WebController::class, 'setSession'])->name('akun-set-session');
Route::get('/tte/{kode}', [WebController::class, 'detailTte'])->name('tte');

// Route::group(['middleware' => 'auth'], function () {
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
Route::get('/akses-surat-masuk', [WebController::class, 'aksesSuratMasuk'])->name('akses-surat-masuk');
Route::get('/akses-disposisi', [WebController::class, 'aksesDisposisi'])->name('akses-disposisi');
Route::get('/pola-spesimen', [WebController::class, 'polaSpesimen'])->name('pola-spesimen');
Route::get('/profil', [WebController::class, 'profil'])->name('profil');
Route::get('/disposisi', [WebController::class, 'disposisi'])->name('disposisi');
Route::get('/distribusi', [WebController::class, 'distribusi'])->name('distribusi');
Route::get('/ttd-elektronik', [WebController::class, 'ttdElektronik'])->name('ttd-elektronik');
Route::get('/disposisi-detail/{id}', [WebController::class, 'disposisiDetail'])->name('disposisi-baca');
Route::get('/surat-masuk-detail/{id}', [WebController::class, 'disposisiDetail'])->name('disposisi-baca');
Route::get('/surat-keluar', [WebController::class, 'suratKeluar'])->name('surat-keluar');
Route::get('/surat-keluar-detail/{id}', [WebController::class, 'suratKeluarDetail'])->name('surat-keluar-detail');

Route::get('/cek-login-eksternal/{web}', [WebController::class, 'cekLoginEksternal'])->name('cek-login-eksternal');

Route::get('/cetak-lembar-disposisi/{id}', [WebController::class, 'cetakLembarDisposisi'])->name('cetak-lembar-disposisi');
Route::get('/cetak-surat-keluar', [WebController::class, 'cetakSuratKeluar'])->name('cetak-surat-keluar');
Route::get('/cetak-surat-masuk', [WebController::class, 'cetakSuratMasuk'])->name('cetak-surat-masuk');
Route::get('/scan-qrcode', [WebController::class, 'scanQrCode'])->name('scan-qrcode');


Route::get('/ujicoba', function () {
    return view('admin.test');
});

Route::get('/kirim-wa/{nomor}/{pesan}', function ($nomor, $pesan) {
    kirimWA($nomor, $pesan);
});

// });

Route::get('/kirim-wa2', function () {
    $curl = curl_init();

    $token = "uvQOIWdPSfeMZKZediZyTGMOGqK0Zn0IhcKlCRcBS5fvWwOZlD14f4MDIiOqdoDs";
    $secret_key = "spz2bNUJ";

    $payload = [
        "data" => [
            [
                'phone' => '085331019999',
                'message' => [
                    'text' => 'WABLAS mantap sekali',
                    'link' => 'https://iainkendari.ac.id/upload/lampiran/IDUP18180454270911793.pdf',
                ],
            ]
        ]
    ];

    curl_setopt(
        $curl,
        CURLOPT_HTTPHEADER,
        array(
            "Authorization: $token.$secret_key",
            "Content-Type: application/json"
        )
    );

    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($curl, CURLOPT_URL,  "https://sby.wablas.com/api/v2/send-link");
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

    $result = curl_exec($curl);
    curl_close($curl);
    echo "<pre>";
    print_r($result);
});
