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
            $query->where('id', $keyword);
        }


        $perPage = $request->input('per_page', env('DATA_PER_PAGE', 10));
        $page = ($perPage == 'all') ? 'all' : $request->input('page', env('DATA_PER_PAGE', 10));

        if ($page === 'all') {
            $data = $query->get();
        } else {
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
            $data = Tujuan::create($validatedData);
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
            return response()->json([
                'success' => false,
                'message' => 'Failed to create',
                'error' => $e->getMessage(),
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
