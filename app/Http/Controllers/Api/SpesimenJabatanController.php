<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\SpesimenJabatanRequest;
use App\Http\Resources\SpesimenJabatanResource;
use App\Models\SpesimenJabatan;

class SpesimenJabatanController extends Controller
{
    //OKE
    public function index(Request $request)
    {
        $query = SpesimenJabatan::with(['user' => function ($query) {
            $query->select('id', 'name', 'email');
        }, 'pejabat.profil', 'pejabat.spesimenJabatanPejabat']);

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
            $query->where('jabatan', 'LIKE', "%$keyword%");
            $query->orWhere('kode', 'LIKE', "%$keyword%");
            $query->orWhere('keterangan', 'LIKE', "%$keyword%");
        }


        $query->orderBy('kode', 'asc')->orderBy('jabatan', 'asc');

        $perPage = $request->input('per_page', env('DATA_PER_PAGE', 10));
        if ($perPage === 'all') {
            $data = $query->get();
        } else {
            $data = $query->paginate($perPage);
        }

        return SpesimenJabatanResource::collection($data);
    }

    //OKE 
    public function findID($id)
    {
        $data = SpesimenJabatan::findOrFail($id);
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
    public function store(SpesimenJabatanRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $data = SpesimenJabatan::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'created successfully',
                'data' => new SpesimenJabatanResource($data),
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
    public function update(SpesimenJabatanRequest $request, $id)
    {

        try {
            $validatedData = $request->validated();
            $data = $this->findId($id);
            $data->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'updated successfully',
                'data' => new SpesimenJabatanResource($data),
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
