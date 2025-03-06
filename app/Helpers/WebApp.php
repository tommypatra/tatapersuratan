<?php


use Carbon\Carbon;
use App\Models\User;
use Hashids\Hashids;
use App\Models\Tujuan;
use App\Models\AksesPola;
use App\Models\PolaSurat;
use App\Models\Distribusi;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use Illuminate\Support\Str;
use App\Jobs\SendMessageJob;
use App\Models\PolaSpesimen;
use App\Models\AksesDisposisi;
use App\Models\SpesimenJabatan;
use App\Models\KlasifikasiSurat;
use App\Http\Resources\TujuanResource;
use App\Http\Resources\DistribusiResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

function kirimWA($phone, $message = null, $jenis = 'text')
{
    SendMessageJob::dispatch($phone, $message, $jenis);
    return response()->json(['message' => 'Pesan sedang diproses dalam antrian.']);
}

function d($var = [], $is_ajax = false)
{
    if ($is_ajax) {
        return response()->json($var, 200);
    } else {
        print_r($var);
    }
    die();
}

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

function getAksesPola($user_id, $tahun)
{
    $query = AksesPola::with(
        [
            'polaSpesimen.polaSurat',
            'polaSpesimen.spesimenJabatan' => function ($query) {
                $query->orderBy('id', 'ASC');
            },
            'user' => function ($query) {
                $query->select('id', 'name', 'email');
            }
        ]
    )
        ->where('tahun', $tahun)
        ->where('user_id', $user_id)
        ->orderBy('tahun', 'desc')
        ->orderBy('user_id', 'asc')
        ->orderBy('pola_spesimen_id', 'asc')
        ->get();

    $data = [
        'pola_spesimen_id' => [],
        'user_pejabat_id' => [],
    ];
    foreach ($query as $i => $dp) {
        $data['pola_spesimen_id'][] = $dp['pola_spesimen_id'];
        $data['user_pejabat_id'][] = $dp['polaSpesimen']->spesimenJabatan->user_pejabat_id;
    }

    if (count($data['pola_spesimen_id']) > 0) {
        $data['pola_spesimen_id'] = array_unique($data['pola_spesimen_id']);
        $data['pola_spesimen_id'] = array_values($data['pola_spesimen_id']);
    }

    if (count($data['user_pejabat_id']) > 0) {
        $data['user_pejabat_id'] = array_unique($data['user_pejabat_id']);
        $data['user_pejabat_id'] = array_values($data['user_pejabat_id']);
    }

    $retval = [
        "success" => true,
        "message" => "data ditemukan",
        "data" => $data,
    ];
    return $retval;
}

function getAksesDisposisi($user_id, $tahun)
{
    $query = AksesDisposisi::with(
        [
            'spesimenJabatan.user',
            'spesimenJabatan' => function ($query) {
                $query->orderBy('id', 'ASC');
            },
            'user' => function ($query) {
                $query->select('id', 'name', 'email');
            }
        ]
    )
        ->where('tahun', $tahun)
        ->where('user_id', $user_id)
        ->orderBy('tahun', 'desc')
        ->orderBy('user_id', 'asc')
        ->orderBy('spesimen_jabatan_id', 'asc')
        ->get();

    $data = [];
    foreach ($query as $i => $dp) {
        $data[$i]['jabatan'] = $dp['spesimenJabatan']->jabatan;
        $data[$i]['user_pejabat_id'] = ($dp['spesimenJabatan']->pejabat) ? $dp['spesimenJabatan']->pejabat->id : "";
        $data[$i]['nama_pejabat'] = ($dp['spesimenJabatan']->pejabat) ? $dp['spesimenJabatan']->pejabat->name : "";
    }

    return $data;
}


function getAdminSpesimen($pola_spesimen_id)
{
    $query = AksesPola::with(
        [
            'user.profil'
        ]
    )->where('pola_spesimen_id', $pola_spesimen_id)->get();

    $retval = [
        "success" => true,
        "message" => "data ditemukan",
        "data" => $query,
    ];
    return $retval;
}

function getInfoSuratMasuk($surat_masuk_id)
{
    $query = SuratMasuk::with([
        'kategoriSuratMasuk',
        'lampiranSuratMasuk.upload',
    ])->where('id', $surat_masuk_id)->first();

    $retval = [
        "success" => true,
        "message" => "data ditemukan",
        "data" => $query,
    ];
    return $retval;
}

function getInfoAkun($user_id)
{
    $query = User::with([
        'profil'
    ])->where('id', $user_id)->first();

    $retval = [
        "success" => true,
        "message" => "data ditemukan",
        "data" => $query,
    ];
    return $retval;
}

function getAdmin()
{
    $query = User::with([
        'profil',
        'grupUser.grup'
    ])->whereHas('grupUser.grup', function ($query) {
        $query->where('grup', 'Admin');
    })->get();

    $retval = [
        "success" => true,
        "message" => "data ditemukan",
        "data" => $query,
    ];
    return $retval;
}

function getIdentitasUser($user_id)
{
    $query = User::with(
        [
            'profil'
        ]
    )->where('id', $user_id)->first();

    $retval = [
        "success" => true,
        "message" => "data ditemukan",
        "data" => $query,
    ];
    return $retval;
}

function getRelasiPolaSpesimen($id)
{
    // Ambil semua data pola spesimen
    $allPolaSpesimen = PolaSpesimen::all();

    // Buat array untuk menyimpan hasil ID yang terkait
    $relatedIds = [];

    // Rekursi untuk mencari semua ID terkait
    $findRelated = function ($targetId) use ($allPolaSpesimen, &$relatedIds, &$findRelated) {
        foreach ($allPolaSpesimen as $item) {
            if ($item->id == $targetId || $item->parent_id == $targetId) {
                if (!in_array($item->id, $relatedIds)) {
                    $relatedIds[] = $item->id;
                    $findRelated($item->id); // Cari anak-anaknya juga
                }
            }
        }
    };

    // Pertama, cari parent jika ada
    $target = $allPolaSpesimen->firstWhere('id', $id);
    if ($target) {
        $findRelated($target->id);

        // Jika item ini adalah anak dari suatu parent, tambahkan parent-nya juga
        if ($target->parent_id !== null && !in_array($target->parent_id, $relatedIds)) {
            $relatedIds[] = $target->parent_id;
            $findRelated($target->parent_id); // Ambil saudara-saudaranya juga
        }
    }

    return $relatedIds;
}


function generateNomorKeluar($tanggal = null, $pola_spesimen_id = null, $klasifikasi_surat_id = null, $no_indeks = null, $no_sub_indeks = null, $id = null)
{
    $retval = [
        'no_surat' => '',
        'no_indeks' => '',
        'no_sub_indeks' => '',
        'pola' => '',
    ];
    // dd($tanggal, $akses_pola_id);
    if (!$tanggal || !$pola_spesimen_id) {
        return $retval;
    }

    // Ambil data dari database
    $persuratan = AksesPola::with(
        [
            'polaSpesimen.polaSurat',
            'polaSpesimen.spesimenJabatan' => function ($query) {
                $query->orderBy('id', 'ASC');
            },
            'user' => function ($query) {
                $query->select('id', 'name', 'email');
            }
        ]
    )->where('pola_spesimen_id', $pola_spesimen_id)->first();
    // dd($persuratan);

    // $ref_pola_spesimen_id = $persuratan->polaSpesimen->parent_id ?? $pola_spesimen_id;
    $ref_pola_spesimen_id = getRelasiPolaSpesimen($pola_spesimen_id);
    // dd($ref_pola_spesimen_id);
    $dt_pola = $persuratan->polaSpesimen->PolaSurat;
    $dt_spesimen_jabatan = $persuratan->polaSpesimen->spesimenJabatan;
    $dt_klasifikasi_surat = KlasifikasiSurat::find($klasifikasi_surat_id);
    // dd($dt_klasifikasi_surat);
    // dd($dt_klasifikasi_surat);
    // Lakukan pengecekan ketersediaan klasifikasi surat jika diperlukan
    if ($dt_pola->needs_klasifikasi && !$dt_klasifikasi_surat) {
        return $retval;
    }

    $pola = $dt_pola->pola;
    $spesimen_jabatan_id = $dt_spesimen_jabatan->id;
    $kode_jabatan = $dt_spesimen_jabatan->kode;
    $kode_klasifikasi_surat = $dt_klasifikasi_surat ? $dt_klasifikasi_surat->kode : null;

    $tanggalObj = Carbon::createFromFormat('Y-m-d', $tanggal);
    // $bln = bulanAngkaToRomawi($tanggalObj->format('m'));
    $bln = $tanggalObj->format('m');
    $thn = $tanggalObj->format('Y');

    $indeks = 1;
    $subindeks = null;

    if (!$no_indeks && !$id) {

        $surat_mundur = SuratKeluar::where('is_diterima', 1)
            ->whereIn('pola_spesimen_id', $ref_pola_spesimen_id)
            ->where(function ($query) use ($thn) {
                $query->whereYear('tanggal', $thn);
            })
            ->where(function ($query) use ($tanggal) {
                $query->where('tanggal', '>', $tanggal);
            })
            ->exists();

        $nomor_sebelumnya = SuratKeluar::select('no_indeks', 'no_sub_indeks')
            ->where('is_diterima', 1)
            ->whereIn('pola_spesimen_id', $ref_pola_spesimen_id)
            ->where(function ($query) use ($thn) {
                $query->whereYear('tanggal', $thn);
            })
            ->where(function ($query) use ($tanggal) {
                $query->where('tanggal', '<=', $tanggal);
            })
            ->orderBy('tanggal', 'desc')
            ->orderBy('no_indeks', 'desc')
            ->orderBy('no_sub_indeks', 'desc')
            ->first();

        if ($surat_mundur) {
            if ($nomor_sebelumnya) {
                $indeks = $nomor_sebelumnya->no_indeks;
                $subindeks = $nomor_sebelumnya->no_sub_indeks;
            } else {
                $nomor_setelahnya = SuratKeluar::select('no_indeks', 'no_sub_indeks')
                    ->where('is_diterima', 1)
                    ->whereIn('pola_spesimen_id', $ref_pola_spesimen_id)
                    ->where(function ($query) use ($thn) {
                        $query->whereYear('tanggal', $thn);
                    })
                    ->where(function ($query) use ($tanggal) {
                        $query->where('tanggal', '>', $tanggal);
                    })
                    ->orderBy('tanggal', 'asc')
                    ->orderBy('no_indeks', 'asc')
                    ->orderBy('no_sub_indeks', 'desc')
                    ->first();
                $indeks = $nomor_setelahnya->no_indeks;
                $subindeks = $nomor_setelahnya->no_sub_indeks;
            }
            // cek dulu indeks dan subindeks
            do {
                $subindeks++;
                $exists = SuratKeluar::where('is_diterima', 1)
                    ->whereIn('pola_spesimen_id', $ref_pola_spesimen_id)
                    ->whereYear('tanggal', $thn)
                    ->where('no_indeks', $indeks)
                    ->where('no_sub_indeks', $subindeks)
                    ->exists();
            } while ($exists);
        } else {
            $indeks = $nomor_sebelumnya->no_indeks + 1;
            $subindeks = null;
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

    dd($retval);
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

// function generateQrCode($data)
// {
//     $pathqr = 'qrcodes/' . date('Y') . '/' . date('m') . '/' . date('d') . '/img-' . $data->id . '-' . time() . '-footer.png';
//     $fullPath = public_path($pathqr); // Pastikan menggunakan public_path untuk path absolut

//     if (!File::exists(dirname($fullPath))) {
//         if (!File::makeDirectory(dirname($fullPath), 0755, true, true)) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Failed to create directory',
//             ], 500);
//         }
//     }
//     $link = url('/') . '/tte/' . $data->kode;
//     $signedBy = strtoupper($data->pejabat);
//     // $qrData = "$link | $signedBy";
//     $qrData = $signedBy;

//     // Generate QR code
//     \QrCode::size(65)->format('png')->generate($qrData, $fullPath);
//     return $pathqr; // Path relatif untuk digunakan di URL
// }

function generateQrCode($data)
{
    $pathqr = 'qrcodes/' . date('Y') . '/' . date('m') . '/' . date('d') . '/img-' . $data->id . '-' . time() . '-footer.png';
    $fullPath = public_path($pathqr); // Pastikan menggunakan public_path untuk path absolut

    if (!File::exists(dirname($fullPath))) {
        if (!File::makeDirectory(dirname($fullPath), 0755, true, true)) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create directory',
            ], 500);
        }
    }
    $link = url('/') . '/tte/' . $data->kode;
    $signedBy = strtoupper($data->pejabat);
    // $qrData = "$link | $signedBy";
    $qrData = $link;

    // Simpan QR Code langsung sebagai file PNG
    QrCode::format('png')->size(300)->margin(2)->errorCorrection('H')->generate($qrData, $fullPath);

    // Cek apakah file QR berhasil dibuat
    if (!File::exists($fullPath)) {
        return response()->json([
            'success' => false,
            'message' => 'QR Code image not found',
        ], 500);
    }

    // Buka kembali QR Code dengan Intervention Image
    $qrImage = Image::make($fullPath);

    // Tambahkan logo jika ada
    $logoPath = public_path('images/logo.png');
    if (File::exists($logoPath)) {
        $logo = Image::make($logoPath)->resize(80, 80);
        $qrImage->insert($logo, 'center');
        // echo "OKE $logoPath";
    }

    // // Buat canvas untuk menambahkan teks
    $canvas = Image::canvas($qrImage->width(), $qrImage->height() + 40, [255, 255, 255, 0]);
    $canvas->insert($qrImage, 'top-center');

    $fontPath = public_path('fonts/arial.ttf'); // Pastikan font ada
    if (File::exists($fontPath)) {
        $canvas->text($signedBy, $qrImage->width() / 2, $qrImage->height() + 20, function ($font) use ($fontPath) {
            $font->file($fontPath);
            $font->size(20);
            $font->color('#000000');
            $font->align('center');
            $font->valign('top');
        });
    }

    // Simpan gambar
    $canvas->save($fullPath, 100);

    return $pathqr; // URL publik untuk digunakan di frontend
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

function cekPort($host = '127.0.0.1', $ports = ['6001'])
{
    foreach ($ports as $port) {
        $connection = @fsockopen($host, $port);
        $return = 0;
        if (is_resource($connection)) {
            $return = 1;
            fclose($connection);
        }
        return $return;
    }
}

function formatNotNull($check = null)
{
    return ($check) ? $check : "";
}

if (!function_exists('daftarAkses')) {
    function daftarAkses($user_id)
    {
        $listAkses = [];
        $getUser = User::with(['grupUser.grup'])->where('id', $user_id)->first();
        if (is_null($getUser)) {
            return [];
        }

        foreach ($getUser->grupUser as $i => $dt) {
            $listAkses[] = ['user_grup_id' => $dt->id, 'user_id' => $dt->user_id, 'grup' => $dt->grup->grup, 'grup_id' => $dt->grup->id];
        }
        // d($listAkses);
        return json_decode(json_encode($listAkses));
    }
}

if (!function_exists('cekGrup')) {

    function cekGrup($daftar_grup, $grup_name)
    {
        $aksesArray = json_decode(json_encode($daftar_grup), true);
        foreach ($aksesArray as $aksesItem) {
            if (strtolower($aksesItem['grup']) === strtolower($grup_name)) {
                return $aksesItem['user_grup_id'];
            }
        }
        return 0;
    }
}


if (!function_exists('getEmailsByGrup')) {
    function getEmailsByGrup(array $grupName)
    {
        return User::with(['grupUser.grup'])
            ->whereHas('grupUser.grup', function ($query) use ($grupName) {
                $query->whereIn('nama', $grupName);
            })
            ->distinct()
            ->pluck('email');
    }
}

if (!function_exists('izinkanAkses')) {
    function izinkanAkses($grup = "global")
    {
        if ($grup != "global") {
            $user = auth()->user();
            $daftar_grup = daftarAkses($user->id);
            $userGrupId = cekGrup($daftar_grup, $grup);
            if (!$userGrupId) {
                return false;
            }
        }
        return true;
    }
}

if (!function_exists('getNomorAgenda')) {
    function getNomorAgenda($kategori, $tahun)
    {
        $query = SuratMasuk::selectRaw("no_agenda, CAST(no_agenda AS UNSIGNED) as num_agenda")
            ->where('is_diterima', 1)
            ->where('kategori_surat', $kategori)
            ->whereYear('tanggal', $tahun)
            ->orderByRaw('CAST(no_agenda AS UNSIGNED) DESC');
        $data = $query->first();
        $no_agenda = "1";
        if ($data) {
            $no_agenda = $data->num_agenda + 1;
        }
        return $no_agenda;
    }
}
