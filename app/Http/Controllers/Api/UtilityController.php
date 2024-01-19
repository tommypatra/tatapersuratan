<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Profil;
use App\Helpers\WebApp;
use App\Models\AksesPola;
use App\Models\TtdQrcode;
use App\Models\SuratMasuk;
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

        $data = $query->get();

        return AksesPolaResource::collection($data);
    }

    public function getSuratMasuk(Request $request)
    {
        $query = SuratMasuk::orderBy('created_at', 'desc')
            ->orderBy('tanggal', 'desc')
            ->orderBy('perihal', 'asc')
            ->with([
                'kategoriSuratMasuk', 'user', 'lampiranSuratMasuk.upload',
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

        $query->where('id', $request->input('id'));
        $data = $query->first();

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

            $user = Profil::where('user_id', auth()->user()->id)->first();
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
            $fileName = auth()->user()->id . '.' . $uploadedFile->guessExtension();
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
            ], 201);
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
        $perPage = $request->input('per_page', env('DATA_PER_PAGE', 10));
        if ($perPage === 'all') {
            $data = $query->get();
        } else {
            $data = $query->paginate($perPage);
        }

        return PolaSpesimenResource::collection($data);
    }
}
