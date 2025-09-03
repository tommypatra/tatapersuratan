<?php

namespace App\Http\Controllers\Api;

use App\Models\PolaSpesimen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PolaSpesimenRequest;
use App\Http\Resources\PolaSpesimenResource;

class PolaSpesimenController extends Controller
{
    //OKE
    public function index(Request $request)
    {
        $query = PolaSpesimen::with(
            [
                'parent.spesimenJabatan',
                'parent.polaSurat',
                'PolaSurat' => function ($query) {
                    $query->orderBy('id', 'ASC');
                },
                'spesimenJabatan' => function ($query) {
                    $query->orderBy('id', 'ASC');
                },
                'user' => function ($query) {
                    $query->select('id', 'name', 'email');
                }
            ]
        )
            ->orderBy('user_id', 'asc')
            ->orderBy('pola_surat_id', 'asc')
            ->orderBy('spesimen_jabatan_id', 'asc');


        //untuk filter lebih dari 1 kolom
        $filter = $request->input('filter');
        if ($filter) {
            $filterArray = json_decode($filter, true);
            if (is_array($filterArray)) {
                foreach ($filterArray as $i => $dp) {
                    $query->where($i, $dp);
                }
            } else {
                // $query->whereHas('tujuan', function ($query) use ($filter) {
                //     $query->where('user_id', $filter);
                // });
            }
        }

        //untuk pencarian
        $keyword = $request->input('keyword');
        if ($keyword) {
            $query->whereHas('user', function ($query) use ($keyword) {
                $query->where('name', 'LIKE', "%" . $keyword . "%")
                    ->orWhere('email', 'LIKE', "%" . $keyword . "%");
            });
        }

        // echo $query->toSql();
        $perPage = $request->input('per_page', env('DATA_PER_PAGE', 10));
        $page = ($perPage == 'all') ? 'all' : $request->input('page', env('DATA_PER_PAGE', 10));
        // echo $perPage;
        if ($page === 'all') {
            $data = $query->get();
        } else {
            $data = $query->paginate($perPage)->onEachSide(1);
        }

        return PolaSpesimenResource::collection($data);
    }

    //OKE 
    public function findID($id)
    {
        $data = PolaSpesimen::findOrFail($id);
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
    public function store(PolaSpesimenRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $validatedData['user_id'] = auth()->user()->id;
            $data = PolaSpesimen::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'created successfully',
                'data' => new PolaSpesimenResource($data),
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
    public function update(PolaSpesimenRequest $request, $id)
    {

        try {
            $validatedData = $request->validated();
            $data = $this->findId($id);
            $data->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'updated successfully',
                'data' => new PolaSpesimenResource($data),
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

    public function hapusParent($id)
    {

        try {
            $data = $this->findId($id);
            $validatedData = [
                'parent_id' => null,
            ];
            $data->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'updated successfully',
                'data' => new PolaSpesimenResource($data),
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
