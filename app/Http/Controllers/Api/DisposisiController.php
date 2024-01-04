<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\DisposisiRequest;
use App\Http\Resources\DisposisiResource;
use App\Models\Disposisi;

class DisposisiController extends Controller
{
    //OKE
    public function index(Request $request)
    {
        $query = Disposisi::orderBy('created_at', 'asc')
            ->with([
                'tujuan.user' => function ($query) {
                    $query->select('id', 'name', 'email');
                },
                'tujuan.suratMasuk' => function ($query) {
                    $query->select('id', 'no_agenda', 'no_surat', 'perihal', 'asal', 'tempat', 'ringkasan', 'tanggal', 'kategori_surat_masuk_id', 'user_id');
                }
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
            } else {
                $query->whereHas('tujuan', function ($query) use ($filter) {
                    $query->where('user_id', $filter);
                });
            }
        }

        //untuk pencarian
        $keyword = $request->input('keyword');
        if ($keyword) {
            $query->where('kategori', 'LIKE', "%$keyword%");
        }

        $perPage = $request->input('per_page', env('DATA_PER_PAGE', 10));
        if ($perPage === 'all') {
            $data = $query->get();
        } else {
            $data = $query->paginate($perPage);
        }
        return DisposisiResource::collection($data);
    }

    //OKE 
    public function findID($id)
    {
        $data = Disposisi::findOrFail($id);
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
    public function store(DisposisiRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $data = Disposisi::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'created successfully',
                'data' => new DisposisiResource($data),
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
    public function update(DisposisiRequest $request, $id)
    {

        try {
            $validatedData = $request->validated();
            $data = $this->findId($id);
            $data->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'updated successfully',
                'data' => new DisposisiResource($data),
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
