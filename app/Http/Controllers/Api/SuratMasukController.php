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

        $rolesAkun = $request->input('roles_akun');
        if (!in_array('Admin', $rolesAkun)) {
            $query->where('user_id', auth()->user()->id);
        }

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
            $query->where('perihal', 'LIKE', "%$keyword%")
                ->orWhere('no_agenda', 'LIKE', "%$keyword%")
                ->orWhere('no_surat', 'LIKE', "%$keyword%")
                ->orWhere('tempat', 'LIKE', "%$keyword%")
                ->orWhere('ringkasan', 'LIKE', "%$keyword%")
                ->orWhere('asal', 'LIKE', "%$keyword%");
        }


        $perPage = $request->input('per_page', env('DATA_PER_PAGE', 10));
        if ($perPage === 'all') {
            $data = $query->get();
        } else {
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
        ], 201);
    }

    //OKE PUT application/x-www-form-urlencoded
    public function store(SuratMasukRequest $request)
    {
        try {
            $validatedData = $request->validated();
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
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
