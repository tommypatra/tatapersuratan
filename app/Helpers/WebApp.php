<?php


use Carbon\Carbon;
use App\Models\User;
use Hashids\Hashids;
use App\Models\Tujuan;
use App\Models\AksesPola;
use App\Models\PolaSurat;
use App\Models\Distribusi;
use App\Models\SuratKeluar;
use Illuminate\Support\Str;
use App\Models\SpesimenJabatan;
use App\Models\KlasifikasiSurat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Http\Resources\TujuanResource;
use App\Http\Resources\DistribusiResource;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


function uploadFile($request)
{
    $uploadedFile = $request->file('file');
    $originalFileName = $uploadedFile->getClientOriginalName();
    $ukuranFile = $uploadedFile->getSize();
    $tipeFile = $uploadedFile->getMimeType();
    $storagePath = 'uploads/' . date('Y') . '/' . date('m') . '/' . date('d');
    if (!File::isDirectory(public_path($storagePath))) {
        File::makeDirectory(public_path($storagePath), 0755, true);
    }
    $fileName = generateUniqueFileName($originalFileName);
    $uploadedFile->move(public_path($storagePath), $fileName);

    $fileFullPath = public_path($storagePath . '/' . $fileName);
    chmod($fileFullPath, 0755);
    // File::chown($fileFullPath, 'www-data');
    // File::chgrp($fileFullPath, 'www-data');
    $path = $storagePath . '/' . $fileName;
    return $path;
}

function generateNomorKeluar($tanggal = null, $akses_pola_id = null, $klasifikasi_surat_id = null, $no_indeks = null, $no_sub_indeks = null, $id = null)
{
    $retval = [
        'no_surat' => '',
        'no_indeks' => '',
        'no_sub_indeks' => '',
        'pola' => '',
    ];
    if (!$tanggal || !$akses_pola_id) {
        return $retval;
    }

    // Ambil data dari database
    $persuratan = AksesPola::with(
        [
            'polaSurat',
            'spesimenJabatan' => function ($query) {
                $query->orderBy('id', 'ASC');
            },
            'user' => function ($query) {
                $query->select('id', 'name', 'email');
            }
        ]
    )->where('id', $akses_pola_id)->first();
    $dt_pola = $persuratan->PolaSurat;
    $dt_spesimen_jabatan = $persuratan->spesimenJabatan;
    $dt_klasifikasi_surat = KlasifikasiSurat::find($klasifikasi_surat_id);

    // Lakukan pengecekan ketersediaan klasifikasi surat jika diperlukan
    if ($dt_pola->needs_klasifikasi && !$dt_klasifikasi_surat) {
        return $retval;
    }

    $pola = $dt_pola->pola;
    $spesimen_jabatan_id = $dt_spesimen_jabatan->id;
    $kode_jabatan = $dt_spesimen_jabatan->kode;
    $kode_klasifikasi_surat = $dt_klasifikasi_surat ? $dt_klasifikasi_surat->kode : null;

    $tanggalObj = Carbon::createFromFormat('Y-m-d', $tanggal);
    $bln = bulanAngkaToRomawi($tanggalObj->format('m'));
    $thn = $tanggalObj->format('Y');

    if (!$no_indeks && !$id) {
        // Cari data surat terbaru untuk menentukan indeks berikutnya
        $lastIndex = SuratKeluar::select([
            \DB::raw('MAX(no_indeks) AS no_indeks'),
            \DB::raw('MIN(tanggal) AS tanggal_awal'),
            \DB::raw('MAX(tanggal) AS tanggal_terakhir'),
            \DB::raw(
                'IF("' . $tanggal . '">=MAX(tanggal),0,
                        IF("' . $tanggal . '" BETWEEN MIN(tanggal) AND MAX(tanggal),1,0)) as mundur'
            ),
        ])
            ->where('akses_pola_id', $akses_pola_id)
            ->whereYear('tanggal', $thn)
            ->first();
        $is_mundur = $lastIndex->mundur;

        //jika mundur maka cari no_indeks dan sub_indeks terakhir pada tanggal tersebut
        if ($is_mundur) {
            $lastIndex = SuratKeluar::select('no_indeks', 'no_sub_indeks')
                ->where('akses_pola_id', $akses_pola_id)
                ->where('tanggal', '<=', $tanggal)
                ->orderBy('no_indeks', 'desc')
                ->orderBy('no_sub_indeks', 'desc')
                ->first();
        }

        $indeks = 1;
        $subindeks = null;
        //jika mundur maka indeks tetap dan sub_indeks tambah 1
        if ($is_mundur) {
            $indeks = $lastIndex->no_indeks;
            //cari maks subindeks berdasarkan indeks
            $cariSub = SuratKeluar::select([
                \DB::raw('MAX(no_sub_indeks) AS no_sub_indeks'),
            ])
                ->where('akses_pola_id', $akses_pola_id)
                ->where('no_indeks', $indeks)
                ->first();
            $subindeks = $cariSub->no_sub_indeks + 1;
        } else {
            if ($lastIndex) {
                $indeks = $lastIndex->no_indeks + 1;
            }
        }
    } else {
        $indeks = $no_indeks;
        $subindeks = $no_sub_indeks;
    }

    //set 3 digit nextindex;
    $nextIndex = str_pad($indeks, 3, '0', STR_PAD_LEFT);
    if ($subindeks)
        $nextIndex .= "." . $subindeks;

    //format nosurat berdasarkan pola
    $no_surat = str_replace(['$index', '$spesimen', '$klasifikasi', '$bln', '$thn'], [$nextIndex, $kode_jabatan, $kode_klasifikasi_surat, $bln, $thn], $pola);

    $retval = [
        'no_surat' => $no_surat,
        'no_indeks' => $indeks,
        'no_sub_indeks' => $subindeks,
        'pola' => $dt_pola->pola,
    ];
    return $retval;
}

function infoDisposisi()
{
    $user = auth()->user()->id;
    $retval = ["success" => false, "message" => "tidak ditemukan", "jumlah_tujuan_belum_diakses" => 0, "data" => []];
    if (!$user) {
        return $retval;
    }
    $jumlahTujuanBelumDiakses = Tujuan::where('user_id', $user)
        ->whereNull('waktu_akses')
        ->count();

    $dataTujuan = Tujuan::where('user_id', $user)
        ->with(
            [
                'user' => function ($query) {
                    $query->select('id', 'name', 'email');
                },
                'suratMasuk',
            ]
        )
        ->orderBy('created_at', 'desc')
        ->whereNull('waktu_akses')
        ->limit(5)
        ->get();

    $retval = [
        "success" => true,
        "message" => "data ditemukan",
        "jumlah_tujuan_belum_diakses" => $jumlahTujuanBelumDiakses,
        "data" => TujuanResource::collection($dataTujuan),
    ];
    return $retval;
}

function infoDistribusi()
{
    $user = auth()->user()->id;
    $retval = ["success" => false, "message" => "tidak ditemukan", "jumlah_distribusi_belum_diakses" => 0, "data" => []];
    if (!$user) {
        return $retval;
    }
    $jumlahDistribusiBelumDiakses = Distribusi::where('user_id', $user)
        ->whereNull('waktu_akses')
        ->count();

    $dataDistribusi = Distribusi::where('user_id', $user)
        ->with(
            [
                'user' => function ($query) {
                    $query->select('id', 'name', 'email');
                },
                'suratKeluar',
            ]
        )
        ->orderBy('created_at', 'desc')
        ->whereNull('waktu_akses')
        ->limit(5)
        ->get();

    $retval = [
        "success" => true,
        "message" => "data ditemukan",
        "jumlah_distribusi_belum_diakses" => $jumlahDistribusiBelumDiakses,
        "data" => DistribusiResource::collection($dataDistribusi),
    ];
    return $retval;
}


function generateUniqueFileName($originalFileName)
{
    $randomString = time() . Str::random(22);
    // $encryptedString = encrypt($randomString);
    $extension = pathinfo($originalFileName, PATHINFO_EXTENSION);
    $uniqueFileName = $randomString . '.' . $extension;
    return $uniqueFileName;
}

function formatNotNull($check = null)
{
    return ($check) ? $check : "";
}

function waktuFormat($dateTime, $format = 'Y-m-d H:i:s')
{
    return Carbon::parse($dateTime)->format($format);
}

function waktu_lalu($timestamp = null)
{
    $waktu = "";
    if ($timestamp) {
        $phpdate = strtotime($timestamp);
        $mysqldate = date('Y-m-d H:i:s', $phpdate);

        $selisih = time() - strtotime($mysqldate);
        $detik = $selisih;
        $menit = round($selisih / 60);
        $jam = round($selisih / 3600);
        $hari = round($selisih / 86400);
        $minggu = round($selisih / 604800);
        $bulan = round($selisih / 2419200);
        $tahun = round($selisih / 29030400);
        if ($detik <= 60) {
            $waktu = $detik . ' detik lalu';
        } else if ($menit <= 60) {
            $waktu = $menit . ' menit lalu';
        } else if ($jam <= 24) {
            $waktu = $jam . ' jam lalu';
        } else if ($hari <= 7) {
            $waktu = $hari . ' hari lalu';
        } else if ($minggu <= 4) {
            $waktu = $minggu . ' minggu lalu';
        } else if ($bulan <= 12) {
            $waktu = $bulan . ' bulan lalu';
        } else {
            $waktu = $tahun . ' tahun lalu';
        }
    }
    return $waktu;
}


function showQRCode($kode, $size = 300)
{
    // $qrCode = QrCode::size($size)->generate($kode);
    $qrCode = QrCode::size($size)->format('svg')->generate($kode);
    $base64 = 'data:image/svg+xml;base64,' . base64_encode($qrCode);
    return $base64;
}

function listAksesHtml($hakakses = [])
{
    // dd($hakakses)
    // $hakakses = (object) $hakakses;
    $retval = '<ul>';
    foreach ($hakakses as $i => $dp) {
        // $dp = (object) $dp;
        $link = route('akun-set-akses', ['grup_id' => $dp['grup']['id']]);
        $retval .= '<li><a href="' . $link . '">' . $dp['grup']['grup'] . '</a></li>';
    }
    $retval .= '</ul>';
    return $retval;
}

function generateToken($length = 64)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = date("smy");
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    $randomString .= date("iHd");
    return $randomString;
}

function generateQrCode($data)
{
    $pathqr = 'qrcodes/' . date('Y') . '/' . date('m') . '/' . date('d') . '/img-' . $data->id . '-' . time() . '-footer.png';
    if (!File::exists(dirname($pathqr))) {
        if (!File::makeDirectory(dirname($pathqr), 0755, true, true)) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create directory',
            ], 500);
        }
    }
    $link = url('/') . '/tte/' . $data->kode;
    // dd($link);
    QrCode::size(65)->format('png')->generate($link, $pathqr);
    return $pathqr;
}


function cekAkses($id = null)
{
    $getAkses = User::with('grupUser.grup')->where('id', $id)->first();
    $grup = [];
    foreach ($getAkses->grupUser as $i => $grp) {
        $grup[] = $grp->grup->grup;
    }
    return $grup;
}


function generateKode($id, $panjang = 32)
{
    $idString = number_format($id, 0, '', '');
    $panjangId = strlen($idString);
    $panjangKarakter = max(1, ceil($panjangId / 8));
    $sisaKarakter = max(0, $panjang - $panjangKarakter);
    $result = str_pad($idString, $panjangKarakter, '0', STR_PAD_LEFT);
    $randomPart = '';
    for ($i = 0; $i < $sisaKarakter; $i++) {
        $randomPart .= mt_rand(0, 9);
    }
    $result .= $randomPart;
    $result = chunk_split($result, 8, '-');
    $result = rtrim($result, '-');
    return $result;
}

function bulanAngkaToRomawi($bulan = 1)
{
    $bulan = (int)$bulan;
    $romawi = [
        1 => 'I',
        2 => 'II',
        3 => 'III',
        4 => 'IV',
        5 => 'V',
        6 => 'VI',
        7 => 'VII',
        8 => 'VIII',
        9 => 'IX',
        10 => 'X',
        11 => 'XI',
        12 => 'XII'
    ];
    return isset($romawi[$bulan]) ? $romawi[$bulan] : 'I';
}
