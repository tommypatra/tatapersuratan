<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\SuratMasukRequest;
use App\Http\Resources\SuratMasukResource;
use App\Models\SuratMasuk;
use Carbon\Carbon;

class SuratMasukController extends Controller
{
    //OKE
    public function index(Request $request)
    {
        $user_id = auth()->user()->id;

        $query = SuratMasuk::orderByRaw('YEAR(tanggal) DESC')
            ->orderByRaw('CAST(no_agenda AS UNSIGNED) DESC')
            // ->orderBy('created_at', 'desc')
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
                    )->orderBy('created_at', 'desc');
                },
            ]);


        //untuk filter lebih dari 1 kolom
        $filter = $request->input('filter');
        $status = "konsep";
        if ($filter) {
            $filterArray = json_decode($filter, true);
            if (is_array($filterArray)) {
                foreach ($filterArray as $i => $dp) {
                    if ($i == 'status')
                        switch ($dp) {
                            case "konsep":
                                $query->where('is_diajukan', '!=', 1);
                                $query->where('user_id', $user_id);
                                $status = "konsep";
                                break;
                            case "diajukan":
                                $query->where('is_diajukan', 1)->whereNull('is_diterima');
                                // if (!izinkanAkses("admin")) {
                                //     $query->where('user_id', $user_id);
                                // }
                                $status = "diajukan";
                                break;
                            case "diterima":
                                $status = "diterima";
                                $query->where('is_diajukan', 1)->where('is_diterima', 1);
                                // if (!izinkanAkses("admin")) {
                                //     $query->where('user_id', $user_id);
                                // }

                                break;
                            case "ditolak":
                                $status = "ditolak";
                                $query->where('is_diajukan', 1)->where('is_diterima', 0);
                                // if (!izinkanAkses("admin")) {
                                //     $query->where('user_id', $user_id);
                                // }
                                break;
                        }
                    elseif ($i == 'tahun') {
                        $tahun_sekarang = $dp;
                        $akses_disposisi = getAksesDisposisi($user_id, $tahun_sekarang);

                        $user_pejabat_id = array_unique(array_column($akses_disposisi, 'user_pejabat_id'));
                        $user_pejabat_id = array_values($user_pejabat_id);

                        // dd($user_pejabat_id);
                        $query->whereYear('tanggal', $tahun_sekarang);
                        if (!empty($user_pejabat_id) && !izinkanAkses("admin")) {

                            $query->Where(function ($query) use ($user_id, $user_pejabat_id) {
                                $query->orWhere('user_id', $user_id);
                                $query->orWhereHas('tujuan', function ($query) use ($user_pejabat_id) {
                                    $query->whereIn('user_id', $user_pejabat_id);
                                });
                            });
                        } else {
                            if (!izinkanAkses("admin")) {
                                $query->where('user_id', auth()->user()->id);
                            }
                        }
                    } elseif ($i == 'kategori') {
                        if ($dp != "SEMUA")
                            $query->where('kategori_surat', $dp);
                    } elseif ($i == 'bulan') {
                        if ($dp != "SEMUA") {
                            $query->whereMonth('tanggal', $dp);
                        }
                    } elseif ($i == 'tanggal') {
                        if ($dp != "SEMUA") {
                            $query->whereDay('tanggal', $dp);
                        }
                    } else
                        $query->where($i, $dp);
                }
            }
        }

        //untuk pencarian
        $keyword = $request->input('keyword');
        if ($keyword) {
            $query->where(function ($query) use ($keyword) {
                $query->where('perihal', 'LIKE', "%$keyword%")
                    ->orWhere('no_agenda', 'LIKE', "%$keyword%")
                    ->orWhere('no_surat', 'LIKE', "%$keyword%")
                    ->orWhere('tempat', 'LIKE', "%$keyword%")
                    ->orWhere('ringkasan', 'LIKE', "%$keyword%")
                    ->orWhere('asal', 'LIKE', "%$keyword%");
                    ->orWhereHas('tujuan.user', function ($query) use ($keyword) {
                        $query->where('name', 'LIKE', "%$keyword%");
                    });                    

            });
        }



        $sql = $query->toSql();
        $bindings = $query->getBindings();
        // dd($sql);

        $perPage = $request->input('per_page', env('DATA_PER_PAGE', 30));
        $page = ($perPage == 'all') ? 'all' : $request->input('page', env('DATA_PER_PAGE', 30));
        if ($page === 'all') {
            $data = $query->get();
        } else {
            $perPage = ($perPage == 'all') ? 20 : $perPage;
            $data = $query->paginate($perPage);
        }
        return SuratMasukResource::collection($data);
    }

    //OKE 
    public function findID($id)
    {
        $data = SuratMasuk::with(['lampiranSuratMasuk.upload'])->findOrFail($id);
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'not found',
            ], 404);
        }
        return $data;
    }

    public function show($id)
    {
        $data = $this->findID($id);
        return response()->json([
            'success' => true,
            'message' => 'ditemukan',
            'data' => $data,
        ], 200);
    }

    public function generateUniqueToken()
    {
        do {
            $tahun = date('Y');
            $kodeAcak = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $token = $tahun . '-' . $kodeAcak;
        } while (SuratMasuk::where('token', $token)->exists());
        return $token;
    }

    //OKE PUT application/x-www-form-urlencoded
    public function store(SuratMasukRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $validatedData['user_id'] = auth()->user()->id;
            $validatedData['token'] = $this->generateUniqueToken();

            $rolesAkun = $request->input('roles_akun');
            if (izinkanAkses("admin")) {
                $validatedData['is_diajukan'] = 1;
                $validatedData['is_diterima'] = 1;
                $validatedData['verifikator'] = auth()->user()->name;

                $tanggal = $validatedData['tanggal'];
                $tahun = Carbon::parse($tanggal)->year;
                $validatedData['no_agenda'] = getNomorAgenda($validatedData['kategori_surat'], $tahun);
            }

            $validatedData['waktu_buat'] = date("Y-m-d H:i:s");
            $data = SuratMasuk::create($validatedData);

            // $data['tanggal']
            return response()->json([
                'success' => true,
                'message' => 'created successfully',
                'data' => new SuratMasukResource($data),
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
                'message' => 'Failed to create',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    //OKE PUT application/x-www-form-urlencoded
    public function update(SuratMasukRequest $request, $id)
    {

        try {
            $validatedData = $request->validated();
            $validatedData['user_id'] = auth()->user()->id;

            $data = $this->findId($id);
            $data->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'updated successfully',
                'data' => new SuratMasukResource($data),
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
                'message' => 'Failed to update',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    //OKE METHOD DELETE/ GET
    public function destroy($id)
    {
        try {
            $data = $this->findId($id);
            $data->delete();

            return response()->json([
                'success' => true,
                'message' => 'deleted successfully',
            ], 204);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function ajukan(Request $request)
    {
        try {
            $data = $this->findId($request->input('id'));
            if ($data->is_diajukan)
                return response()->json([
                    'success' => false,
                    'message' => 'pengajuan gagal',
                    'error' => 'sudah diajukan',
                ], 500);


            $data->update(['is_diajukan' => 1]);

            $admin = getAdmin();
            foreach ($admin['data'] as $i => $item) {
                $pesanWA = "Hai, " . $item->name . " ada ajuan surat masuk untuk proses disposisi dari " . auth()->user()->name . ", ";
                $pesanWA .= "surat berasal dari " . $data->tempat . " (" . $data->asal . ") tentang " . $data->perihal . ", nomor " . $data->no_surat . ", tertanggal " . $data->tanggal . " ";
                $pesanWA .= "mohon untuk diproses.\n\n";
                $pesanWA .= "silahkan cek dengan login laman https://surat.iainkendari.ac.id/";
                if ($item->profil->hp) {
                    kirimWA($item->profil->hp, $pesanWA);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'pengajuan successfully',
                'data' => new SuratMasukResource($data),
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
                'message' => 'Failed to update',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function prosesAjuan(Request $request)
    {
        try {
            $dataSave = [
                'is_diterima' => $request->input('is_diterima'),
                'catatan' => $request->input('catatan'),
                'verifikator' => auth()->user()->name,
            ];

            $data = $this->findId($request->input('id'));
            if ($request->input('is_diterima') == 1) {
                $tahun = Carbon::parse($data->tanggal);
                $dataSave['no_agenda'] = getNomorAgenda($data->kategori_surat, $tahun);
            }
            $data->update($dataSave);

            $akun = getInfoAkun($data->user_id);
            if ($akun['data']) {

                $perihal = $data->perihal;
                $pesanWA = "Hai, " . $akun['data']->name . " ajuan surat tentang " . $data->perihal . " tertanggal " . $data->tanggal . " ";
                if ($request->input('is_diterima') == 1) {
                    $pesanWA .= "sudah diterima dan dalam proses disposisi.\n\n";
                } else {
                    $pesanWA .= "ditolak dan tidak diproses disposisinya";
                    if ($request->input('catatan'))
                        $pesanWA .= " karena " . $request->input('catatan') . ".\n\n";
                }
                $pesanWA .= "silahkan cek dengan login laman https://surat.iainkendari.ac.id/";

                if ($akun['data']->profil->hp) {
                    kirimWA($akun['data']->profil->hp, $pesanWA);
                }
            }


            return response()->json([
                'success' => true,
                'message' => 'Update data sukses dilakukan',
                'data' => $dataSave,
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
                'message' => 'Failed to update',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function kembalikanSuratMasuk(Request $request, $id)
    {
        try {
            $dataSave = [
                'catatan' => $request->input('catatan'),
                'is_diajukan' => 0,
                'is_diterima' => null,
            ];

            $data = $this->findId($id);
            $data->update($dataSave);

            $akun = getInfoAkun($data->user_id);
            if ($akun['data']) {

                $perihal = $data->perihal;
                $pesanWA = "Hai, " . $akun['data']->name . " ajuan surat masuk tentang " . $data->perihal . " tertanggal " . $data->tanggal . " ";
                $pesanWA .= "telah dikemblikan, silahkan perbaiki sesuai catatan dan ajukan kembali.\n\n";
                $pesanWA .= "silahkan cek dengan login laman https://surat.iainkendari.ac.id/";

                if ($akun['data']->profil->hp) {
                    kirimWA($akun['data']->profil->hp, $pesanWA);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Update data sukses dilakukan',
                'data' => $dataSave,
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
                'message' => 'Failed to update',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
