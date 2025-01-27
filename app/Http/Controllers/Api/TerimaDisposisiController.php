<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\TerimaDisposisiRequest;
use App\Http\Resources\TerimaDisposisiResource;
use App\Models\TerimaDisposisi;

class TerimaDisposisiController extends Controller
{
    //OKE
    public function index(Request $request)
    {
        $query = TerimaDisposisi::orderBy('id', 'desc')
            ->with(['user', 'suratMasuk']);

        //untuk filter lebih dari 1 kolom

        $perPage = $request->input('per_page', env('DATA_PER_PAGE', 10));
        $page = ($perPage == 'all') ? 'all' : $request->input('page', env('DATA_PER_PAGE', 10));

        if ($page === 'all') {
            $data = $query->get();
        } else {
            $perPage = ($perPage == 'all') ? 20 : 20;

            $data = $query->paginate($perPage);
        }

        return TerimaDisposisiResource::collection($data);
    }

    //OKE 
    public function findID($id)
    {
        $data = TerimaDisposisi::findOrFail($id);
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
    public function store(TerimaDisposisiRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $validatedData['user_id'] = auth()->user()->id;

            $data = TerimaDisposisi::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'created successfully',
                'data' => new TerimaDisposisiResource($data),
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
    public function update(TerimaDisposisiRequest $request, $id)
    {

        try {
            $validatedData = $request->validated();
            $validatedData['user_id'] = auth()->user()->id;

            $data = $this->findId($id);
            $data->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'updated successfully',
                'data' => new TerimaDisposisiResource($data),
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
