<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\SuratMasukRequest;
use App\Http\Resources\SuratMasukResource;
use App\Models\SuratMasuk;

class SuratMasukController extends Controller
{
    //OKE
    public function index(Request $request)
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
                    )->orderBy('created_at', 'desc');
                },
            ]);


        //untuk filter lebih dari 1 kolom
        $filter = $request->input('filter');
        if ($filter) {
            $filterArray = json_decode($filter, true);
            if (is_array($filterArray)) {
                foreach ($filterArray as $i => $dp) {
                    if ($i == 'kategori')
                        switch ($dp) {
                            case "konsep":
                                $query->where('is_diajukan', '!=', 1);
                                break;
                            case "diajukan":
                                $query->where('is_diajukan', 1)->whereNull('is_diterima');
                                break;
                            case "diterima":
                                $query->where('is_diajukan', 1)->where('is_diterima', 1);
                                break;
                            case "ditolak":
                                $query->where('is_diajukan', 1)->where('is_diterima', 0);
                                break;
                        }
                    elseif ($i == 'tahun') {
                        $tahun_sekarang = $dp;
                        $query->whereYear('tanggal', $tahun_sekarang);
                    } elseif ($i == 'bulan') {
                        $bulan_sekarang = $dp;
                        $query->whereMonth('tanggal', $bulan_sekarang);
                    } else
                        $query->where($i, $dp);
                }
            }
        }
        $sql = $query->toSql();
        //untuk pencarian
        $keyword = $request->input('keyword');
        if ($keyword) {
            $query->where('perihal', 'LIKE', "%$keyword%")
                ->orWhere('no_agenda', 'LIKE', "%$keyword%")
                ->orWhere('no_surat', 'LIKE', "%$keyword%")
                ->orWhere('tempat', 'LIKE', "%$keyword%")
                ->orWhere('ringkasan', 'LIKE', "%$keyword%")
                ->orWhere('asal', 'LIKE', "%$keyword%");
        }

        if (!izinkanAkses("admin")) {
            $query->where('user_id', auth()->user()->id);
        }

        $perPage = $request->input('per_page', env('DATA_PER_PAGE', 10));
        $page = ($perPage == 'all') ? 'all' : $request->input('page', env('DATA_PER_PAGE', 10));
        if ($page === 'all') {
            $data = $query->get();
        } else {
            $perPage = ($perPage == 'all') ? 20 : 20;

            $data = $query->paginate($perPage);
        }
        return SuratMasukResource::collection($data);
    }

    //OKE 
    public function findID($id)
    {
        $data = SuratMasuk::findOrFail($id);
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

    //OKE PUT application/x-www-form-urlencoded
    public function store(SuratMasukRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $validatedData['user_id'] = auth()->user()->id;

            $rolesAkun = $request->input('roles_akun');
            if (izinkanAkses("admin")) {
                $validatedData['is_diajukan'] = 1;
                $validatedData['is_diterima'] = 1;
                $validatedData['verifikator'] = auth()->user()->name;
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

            $data->update($dataSave);

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
