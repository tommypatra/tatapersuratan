<?php

namespace App\Http\Controllers\Api;

use App\Models\Tujuan;
use Illuminate\Http\Request;
use App\Models\TerimaDisposisi;
use App\Http\Controllers\Controller;
use App\Http\Requests\TujuanRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\TujuanResource;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

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
        DB::beginTransaction();
        try {
            $validatedData = $request->validated();

            $surat_masuk = getInfoSuratMasuk($validatedData['surat_masuk_id']);
            $akun = getInfoAkun($validatedData['user_id']);
            // dd($surat_masuk['data']);
            // dd($akun['data']);

            $data = Tujuan::create($validatedData);

            $data_terima_disposisi = [
                'user_id' => auth()->user()->id,
                'tujuan_id' => $data->id,
            ];
            $data = TerimaDisposisi::create($data_terima_disposisi);


            //script kirim WA otomatis
            if ($akun['data']) {
                $perihal = $surat_masuk['data']->perihal;
                $pesanWA = "Hai, " . $akun['data']->name . " ada ajuan surat masuk/ disposisi, ";
                $pesanWA .= "surat berasal dari " . $surat_masuk['data']->tempat . " " . $surat_masuk['data']->asal . " tentang " . $perihal . ", nomor " . $surat_masuk['data']->no_surat . ", tertanggal " . $surat_masuk['data']->tanggal . " ";
                $pesanWA .= "mohon untuk segera diproses.\n\n";
                $pesanWA .= "silahkan cek dengan login laman https://surat.iainkendari.ac.id/";

                if ($akun['data']->profil->hp) {
                    kirimWA($akun['data']->profil->hp, $pesanWA);
                    // nanti kirim dokumen sekalian
                    // if ($akun['data']->lampiranSuratMasuk)
                    //     foreach ($akun['data']->lampiranSuratMasuk as $i => $item) {
                    //         kirimWA($akun['data']->profil->hp, "<a href='" . url('/' . $item['link']) . "'>Dokumen " . $perihal . " " . ($i + 1) . "</a>", "document");
                    //     }
                }
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'created successfully',
                'data' => new TujuanResource($data),
            ], 201);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
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
