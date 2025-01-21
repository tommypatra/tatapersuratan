<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\TujuanRequest;
use App\Http\Resources\TujuanResource;
use App\Models\Tujuan;

class TujuanController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $query = Tujuan::orderBy('created_at', 'desc')
            ->with([
                'user' => function ($query) {
                    $query->select('id', 'name', 'email');
                },
                // 'suratMasuk',
                'suratMasuk.tujuan.disposisi',
                'disposisi',
            ]);

        $query->where('user_id', auth()->user()->id);


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
                // ->with(['disposisi'])->where('user_id', $filter);
            }
        }

        //untuk pencarian
        $keyword = $request->input('keyword');
        if ($keyword) {
            $query->where(function ($query) use ($keyword) {
                $query->where('id', $keyword);
            });
        }


        $perPage = $request->input('per_page', env('DATA_PER_PAGE', 10));
        $page = ($perPage == 'all') ? 'all' : $request->input('page', env('DATA_PER_PAGE', 10));

        // echo auth()->user()->id;
        // $sql = $query->toSql();
        // $bindings = $query->getBindings();
        // dd($sql);

        if ($page === 'all') {
            $data = $query->get();
        } else {
            $perPage = ($perPage == 'all') ? 20 : 20;

            $data = $query->paginate($perPage);
        }
        return TujuanResource::collection($data);
    }

    //OKE 
    public function findID($id)
    {
        $data = Tujuan::findOrFail($id);
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
    public function store(TujuanRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $surat_masuk = getInfoSuratMasuk($validatedData['surat_masuk_id']);
            $akun = getInfoAkun($validatedData['user_id']);
            // dd($surat_masuk['data']);
            // dd($akun['data']);

            $data = Tujuan::create($validatedData);

            //script kirim WA otomatis
            if ($akun['data']) {
                $pesanWA = "Hai, " . $akun['data']->name . " ada ajuan surat masuk/ disposisi, ";
                $pesanWA .= "surat berasal dari " . $surat_masuk['data']->tempat . " " . $surat_masuk['data']->asal . " tentang " . $surat_masuk['data']->perihal . ", nomor " . $surat_masuk['data']->no_surat . ", tertanggal " . $data->tanggal . " ";
                $pesanWA .= "mohon untuk segera diproses.\n\n";
                $pesanWA .= "silahkan cek dengan login laman https://surat.iainkendari.ac.id/";
                if ($akun['data']->profil->hp) {
                    kirimWA($item->profil->hp, $pesanWA);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'created successfully',
                'data' => new TujuanResource($data),
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return response()->json([
                    'success' => false,
                    'message' => ['Data duplikat: surat sudah terdisposisi pada akun tujuan tersebut.'],
                ], 400); // Status HTTP 400 atau lainnya sesuai kebutuhan
            }

            return response()->json([
                'success' => false,
                'message' => [$e->getMessage()],
            ], 500);
        }
    }

    //OKE PUT application/x-www-form-urlencoded
    public function update(TujuanRequest $request, $id)
    {

        try {
            $validatedData = $request->validated();
            // dd($validatedData);
            $data = $this->findId($id);
            $data->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'updated successfully',
                'data' => new TujuanResource($data),
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
}
