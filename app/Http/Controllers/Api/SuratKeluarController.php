<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\SuratKeluarRequest;
use App\Http\Resources\SuratKeluarResource;
use App\Models\SuratKeluar;

class SuratKeluarController extends Controller
{
    //OKE
    public function index(Request $request)
    {
        $query = SuratKeluar::orderBy('tanggal', 'desc')
            ->orderBy('no_indeks', 'desc')
            ->orderBy('no_sub_indeks', 'desc')
            ->orderBy('perihal', 'asc')
            ->with([
                'klasifikasiSurat', 'user', 'lampiranSuratKeluar.upload',
                'aksesPola.polaSurat', 'aksesPola.spesimenJabatan', 'distribusi.user' => function ($query) {
                    $query->select('id', 'name', 'email');
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
            if (is_array($filterArray)) {
                foreach ($filterArray as $i => $dp) {
                    $query->where($i, $dp);
                }
            }
        }

        //untuk pencarian
        $keyword = $request->input('keyword');
        if ($keyword) {
            $query->where('perihal', 'LIKE', "%$keyword%")
                ->orWhere('no_surat', 'LIKE', "%$keyword%")
                ->orWhere('ringkasan', 'LIKE', "%$keyword%")
                ->orWhere('asal', 'LIKE', "%$keyword%");
        }

        $perPage = $request->input('per_page', env('DATA_PER_PAGE', 10));
        if ($perPage === 'all') {
            $data = $query->get();
        } else {
            $data = $query->paginate($perPage);
        }
        return SuratKeluarResource::collection($data);
    }

    //OKE 
    public function findID($id)
    {
        $data = SuratKeluar::findOrFail($id);
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
    public function store(SuratKeluarRequest $request)
    {
        try {
            $validatedData = $request->validated();
            // $validatedData['waktu_buat'] = date("Y-m-d H:i:s");
            // dd($validatedData);
            $data = SuratKeluar::create($validatedData);

            // $data['tanggal']
            return response()->json([
                'success' => true,
                'message' => 'created successfully',
                'data' => new SuratKeluarResource($data),
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
    public function update(SuratKeluarRequest $request, $id)
    {

        try {
            $validatedData = $request->validated();
            $data = $this->findId($id);
            $data->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'updated successfully',
                'data' => new SuratKeluarResource($data),
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
