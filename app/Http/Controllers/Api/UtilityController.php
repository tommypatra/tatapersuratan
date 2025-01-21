<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Profil;
use App\Helpers\WebApp;
use App\Models\AksesPola;
use App\Models\TtdQrcode;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\PolaSpesimen;

use Illuminate\Http\Request;
use App\Models\KlasifikasiSurat;
use App\Models\KategoriSuratMasuk;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Resources\UserAppResource;
use App\Http\Resources\AksesPolaResource;
use App\Http\Resources\TtdQrcodeResource;
use App\Http\Resources\SuratMasukResource;
use App\Http\Resources\PolaSpesimenResource;
use App\Http\Resources\AksesDisposisiResource;
use App\Http\Resources\KlasifikasiSuratResource;
use App\Http\Resources\KategoriSuratMasukResource;

class UtilityController extends Controller
{
    function infoDisposisi()
    {
        $infoDisposisi = infoDisposisi();
        return response()->json($infoDisposisi, 200);
    }

    function infoDistribusi()
    {
        $infoDistribusi = infoDistribusi();
        return response()->json($infoDistribusi, 200);
    }

    function detailDisposisi()
    {
        $infoDisposisi = infoDisposisi();
        return response()->json($infoDisposisi, 200);
    }

    public function encode($id)
    {
        $encode = encode($id);
        return response()->json(['encoded' => $encode], 200);
    }

    public function decode($encoded)
    {
        $decoded = decode($encoded);
        return response()->json(['decoded' => $decoded], 200);
    }


    public function getAksesDisposisi(Request $request)
    {
        //untuk filter lebih dari 1 kolom
        $tahun = $request->input('tahun');

        $data = getAksesDisposisi(auth()->user()->id, $tahun);

        return response()->json(['data' => $data], 200);
    }


    public function getAksesPola(Request $request)
    {
        $query = AksesPola::with(
            [
                'polaSpesimen.polaSurat',
                'polaSpesimen.spesimenJabatan.pejabat',
                'user' => function ($query) {
                    $query->select('id', 'name', 'email');
                }
            ]
        )
            ->orderBy('tahun', 'desc')
            ->orderBy('user_id', 'asc')
            ->orderBy('pola_spesimen_id', 'asc');

        //untuk filter lebih dari 1 kolom
        $filter = $request->input('filter');
        if ($filter) {
            $filterArray = json_decode($filter, true);
            if (is_array($filterArray)) {
                foreach ($filterArray as $i => $dp) {
                    if ($i == 'user_id_login') {
                        $query->where("user_id", auth()->user()->id);
                    } else if ($i == 'tahun') {
                        $query->where("tahun", $dp)->where("user_id", auth()->user()->id);
                    } else
                        $query->where($i, $dp);
                }
            } else {
                // $query->whereHas('tujuan', function ($query) use ($filter) {
                //     $query->where('user_id', $filter);
                // });
            }
        }

        //untuk pencarian
        $keyword = $request->input('keyword');
        if ($keyword) {
            $query->whereHas('user', function ($query) use ($keyword) {
                $query->where('name', 'LIKE', "%" . $keyword . "%")
                    ->orWhere('email', 'LIKE', "%" . $keyword . "%");
            });
        }
        $sql = $query->toSql();
        $bindings = $query->getBindings();
        // dd($sql);?

        $data = $query->get();

        return AksesPolaResource::collection($data);
    }

    public function getSuratMasuk(Request $request)
    {
        $query = SuratMasuk::orderBy('created_at', 'desc')
            ->orderBy('tanggal', 'desc')
            ->orderBy('perihal', 'asc')
            ->with([
                'kategoriSuratMasuk',
                'user',
                'lampiranSuratMasuk.upload',
                'tujuan' => function ($query) {
                    $query->with(
                        [
                            'user' => function ($query) {
                                $query->select('id', 'name', 'email');
                            },
                            'disposisi.user' => function ($query) {
                                $query->select('id', 'name', 'email');
                            }
                        ]
                    )->orderBy('created_at', 'asc');
                },
            ]);

        if ($request->input('id'))
            $query->where('id', $request->input('id'));
        elseif ($request->input('nomor_surat'))
            $query->where('token', $request->input('nomor_surat'));
        else
            $query->where('id', 0);

        $data = $query->first();
        if (!$data)
            return response()->json([
                'success' => false,
                'message' => 'tidak ditemukan',
                'data' => [],
            ], 404);

        return response()->json([
            'success' => true,
            'message' => 'data ditemukan',
            'data' => new SuratMasukResource($data),
        ], 200);
    }

    public function getKategoriSuratMasuk(Request $request)
    {
        $query = KategoriSuratMasuk::orderBy('kategori', 'asc')
            ->with(['user']);

        //untuk filter lebih dari 1 kolom
        $filter = $request->input('filter');
        if ($filter) {
            $filterArray = json_decode($filter, true);
            if ($filterArray) {
                foreach ($filterArray as $i => $dp) {
                    $query->where($i, $dp);
                }
            }
        }

        //untuk pencarian
        $keyword = $request->input('keyword');
        if ($keyword) {
            $query->where('kategori', 'LIKE', "%$keyword%");
        }
        $data = $query->get();

        return KategoriSuratMasukResource::collection($data);
    }

    public function getUsers(Request $request)
    {
        $keyword = $request->input('keyword');
        $query = User::orderBy('name', 'asc')
            ->with([
                'grupUser.grup',
                'profil' => function ($query) use ($keyword) {
                    if ($keyword) {
                        $query->where('alamat', 'LIKE', "%$keyword%")
                            ->orWhere('hp', 'LIKE', "%$keyword%");
                    }
                },
                'spesimenJabatanPejabat'
            ]);

        //untuk filter lebih dari 1 kolom
        $filter = $request->input('filter');
        if ($filter) {
            $filterArray = json_decode($filter, true);
            if (is_array($filterArray)) {
                foreach ($filterArray as $i => $dp) {
                    $query->where($i, $dp);
                }
            } else {
                $query->where('user_id', $filter);
            }
        }

        //untuk pencarian
        $keyword = $request->input('keyword');
        if ($keyword) {
            $query->where('name', 'LIKE', "%$keyword%")
                ->orWhere('email', 'LIKE', "%$keyword%");
        }

        $data = $query->get();
        return UserAppResource::collection($data);
    }


    public function gantiFotoProfil(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:3000',
            ]);
            $user_id = auth()->user()->id;

            $user = Profil::where('user_id', $user_id)->first();
            if ($user->foto) {
                File::delete(public_path($user->foto));
            }
            //upload proses
            $uploadedFile = $request->file('foto');
            $originalFileName = $uploadedFile->getClientOriginalName();
            $ukuranFile = $uploadedFile->getSize();
            $tipeFile = $uploadedFile->getMimeType();
            $storagePath = 'foto/';
            if (!File::isDirectory(public_path($storagePath))) {
                File::makeDirectory(public_path($storagePath), 0755, true);
            }
            $fileName = $user_id . '.' . $uploadedFile->guessExtension();
            $uploadedFile->move(public_path($storagePath), $fileName);

            //simpan ke database
            $user->update([
                'foto' => $storagePath . '/' . $fileName,
            ]);

            session()->put('foto', $user->foto);

            return response()->json([
                'success' => true,
                'message' => 'foto profil berhasil terupdate',
                'data' => $user,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function tte($kode)
    {
        $query = TtdQrcode::orderBy('is_diterima', 'asc')
            ->orderBy('tanggal', 'asc')
            ->orderBy('perihal', 'asc')
            ->where('kode', $kode)
            ->with([
                'user' => function ($query) {
                    $query->select('id', 'name', 'email');
                },
                'ttd' => function ($query) {
                    $query->select('id', 'name', 'email');
                },
            ]);


        $data = $query->first();
        // dd($data);

        $barcode = generateQrCode($data);
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'data ditemukan',
            'data' => $data,
        ], 200);
    }

    public function getKlasifikasiSuratKeluar(Request $request)
    {
        $query = KlasifikasiSurat::orderBy('kode', 'asc')
            ->with(['user' => function ($query) {
                $query->select('id', 'name', 'email');
            },]);

        $filter = $request->input('filter');
        if ($filter) {
            $filterArray = json_decode($filter, true);
            if ($filterArray) {
                foreach ($filterArray as $i => $dp) {
                    $query->where($i, $dp);
                }
            }
        }

        $keyword = $request->input('keyword');
        if ($keyword) {
            $query->where('kode', 'LIKE', "%$keyword%");
            $query->orWhere('klasifikasi', 'LIKE', "%$keyword%");
            $query->orWhere('keterangan', 'LIKE', "%$keyword%");
        }

        $data = $query->get();

        return KlasifikasiSuratResource::collection($data);
    }

    public function getPolaSpesimen(Request $request)
    {
        $query = PolaSpesimen::with(
            [
                'PolaSurat' => function ($query) {
                    $query->orderBy('id', 'ASC');
                },
                'spesimenJabatan' => function ($query) {
                    $query->orderBy('id', 'ASC');
                },
                'user' => function ($query) {
                    $query->select('id', 'name', 'email');
                }
            ]
        )
            ->orderBy('user_id', 'asc')
            ->orderBy('pola_surat_id', 'asc')
            ->orderBy('spesimen_jabatan_id', 'asc');


        //untuk filter lebih dari 1 kolom
        $filter = $request->input('filter');
        if ($filter) {
            $filterArray = json_decode($filter, true);
            if (is_array($filterArray)) {
                foreach ($filterArray as $i => $dp) {
                    $query->where($i, $dp);
                }
            } else {
                // $query->whereHas('tujuan', function ($query) use ($filter) {
                //     $query->where('user_id', $filter);
                // });
            }
        }

        //untuk pencarian
        $keyword = $request->input('keyword');
        if ($keyword) {
            $query->whereHas('user', function ($query) use ($keyword) {
                $query->where('name', 'LIKE', "%" . $keyword . "%")
                    ->orWhere('email', 'LIKE', "%" . $keyword . "%");
            });
        }

        // echo $query->toSql();
        $perPage = $request->input('page', env('DATA_PER_PAGE', 10));
        if ($perPage === 'all') {
            $data = $query->get();
        } else {
            $data = $query->paginate($perPage);
        }

        return PolaSpesimenResource::collection($data);
    }


    public function dataInfo($data)
    {
        $totalKonsep = 0;
        $totalDiajukan = 0;
        $totalDiterima = 0;
        $totalDitolak = 0;

        $bulanArray = range(0, 11);
        $perbulan = [];
        $grafik = [];
        foreach ($bulanArray as $bulan) {
            $konsep = isset($data[$bulan]) ? (int)$data[$bulan]['konsep'] : 0;
            $diajukan = isset($data[$bulan]) ? (int)$data[$bulan]['diajukan'] : 0;
            $diterima = isset($data[$bulan]) ? (int)$data[$bulan]['diterima'] : 0;
            $ditolak = isset($data[$bulan]) ? (int)$data[$bulan]['ditolak'] : 0;
            $total = $konsep + $diajukan + $diterima + $ditolak;
            $perbulan[] = [
                'konsep' => $konsep,
                'diajukan' => $diajukan,
                'diterima' => $diterima,
                'ditolak' => $ditolak,
                'total' => $total,
                'bulan' => isset($data[$bulan]) ? (int)$data[$bulan]['bulan'] : $bulan + 1,
            ];

            $grafik[$bulan] = $total;
        }

        $retVal['grafik'] = $grafik;
        $retVal['perbulan'] = $perbulan;

        foreach ($data as $bulanData) {
            $totalKonsep += $bulanData['konsep'];
            $totalDiajukan += $bulanData['diajukan'];
            $totalDiterima += $bulanData['diterima'];
            $totalDitolak += $bulanData['ditolak'];
        }
        $retVal['total'] = [
            'konsep' => $totalKonsep,
            'diajukan' => $totalDiajukan,
            'diterima' => $totalDiterima,
            'ditolak' => $totalDitolak,
        ];
        return $retVal;
    }

    public function infoGeneral()
    {
        try {
            $user_id = auth()->user()->id;
            $rolesAkun = cekAkses($user_id);
            $tahun_sekarang = date('Y');

            //untuk info surat masuk
            $suratMasuk = SuratMasuk::selectRaw("
                COUNT(CASE WHEN is_diajukan = 0 THEN 1 END) as konsep,
                COUNT(CASE WHEN is_diajukan = 1 AND is_diterima IS NULL THEN 1 END) as diajukan,
                COUNT(CASE WHEN is_diajukan = 1 AND is_diterima = 1 THEN 1 END) as diterima,
                COUNT(CASE WHEN is_diajukan = 1 AND is_diterima = 0 THEN 1 END) as ditolak,
                DATE_FORMAT(tanggal, '%c') AS bulan
            ")
                ->whereYear('tanggal', $tahun_sekarang)
                ->orderBy('bulan')
                ->groupBy('bulan');
            if (!in_array('Admin', $rolesAkun)) {
                $suratMasuk->where('user_id', $user_id);
            }
            $runQuery = $suratMasuk->get();
            $data['surat_masuk'] = $this->dataInfo($runQuery);

            //untuk info ttd qrcode
            $ttd = TtdQrcode::selectRaw("
                COUNT(CASE WHEN is_diajukan = 0 THEN 1 END) as konsep,
                COUNT(CASE WHEN is_diajukan = 1 AND is_diterima IS NULL THEN 1 END) as diajukan,
                COUNT(CASE WHEN is_diajukan = 1 AND is_diterima = 1 THEN 1 END) as diterima,
                COUNT(CASE WHEN is_diajukan = 1 AND is_diterima = 0 THEN 1 END) as ditolak,
                DATE_FORMAT(tanggal, '%c') AS bulan
            ")
                ->where(function ($query) use ($user_id) {
                    $query->where('user_id', $user_id)
                        ->orWhere('user_ttd_id', $user_id);
                })
                ->whereYear('tanggal', $tahun_sekarang)
                ->orderBy('bulan')
                ->groupBy('bulan');
            $runQuery = $ttd->get();
            $data['ttd'] = $this->dataInfo($runQuery);

            //untuk info surat keluar
            $aksespola = getAksesPola($user_id, $tahun_sekarang);
            $suratKeluar = SuratKeluar::selectRaw("
                COUNT(CASE WHEN is_diajukan = 0 THEN 1 END) as konsep,
                COUNT(CASE WHEN is_diajukan = 1 AND is_diterima IS NULL THEN 1 END) as diajukan,
                COUNT(CASE WHEN is_diajukan = 1 AND is_diterima = 1 THEN 1 END) as diterima,
                COUNT(CASE WHEN is_diajukan = 1 AND is_diterima = 0 THEN 1 END) as ditolak,
                DATE_FORMAT(tanggal, '%c') AS bulan
            ")
                ->whereYear('tanggal', $tahun_sekarang)
                ->orderBy('bulan')
                ->groupBy('bulan');

            if (!empty($aksespola['data'])) {
                $idAkses = $aksespola['data'];
                $suratKeluar->where(function ($query) use ($user_id, $idAkses) {
                    $query->orWhere('user_id', $user_id)
                        ->orWhere(function ($query) use ($idAkses) {
                            $query->whereIn('pola_spesimen_id', $idAkses);
                        });
                });
            } else {
                if (!in_array('Admin', $rolesAkun)) {
                    $query->where('user_id', $user_id);
                }
            }
            $runQuery = $suratKeluar->get();
            $data['surat_keluar'] = $this->dataInfo($runQuery);

            return response()->json([
                'success' => true,
                'message' => 'data ditemukan',
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function cekAkses($grup)
    {
        if (!izinkanAkses($grup)) {
            return response()->json(['success' => false, 'message' => 'akses ditolak'], 403);
        }
        return response()->json(['success' => true, 'message' => 'akses diperbolehkan'], 200);
    }
}
