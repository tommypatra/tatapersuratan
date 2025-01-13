<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\LampiranSuratKeluarRequest;
use App\Http\Resources\LampiranSuratKeluarResource;
use App\Models\LampiranSuratKeluar;
use App\Models\Upload;

class LampiranSuratKeluarController extends Controller
{
    //OKE
    public function index(Request $request)
    {
        $query = LampiranSuratKeluar::orderBy('surat_keluar_id', 'desc')
            ->with(['suratKeluar', 'upload.user']);

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
            $query->whereHas('suratKeluar', function ($query) use ($keyword) {
                $query->where('perihal', 'LIKE', "%$keyword%")
                    ->orWhere('no_surat', 'LIKE', "%$keyword%")
                    ->orWhere('tujuan', 'LIKE', "%$keyword%")
                    ->orWhere('ringkasan', 'LIKE', "%$keyword%")
                    ->orWhere('asal', 'LIKE', "%$keyword%");
            });
        }

        $perPage = $request->input('per_page', env('DATA_PER_PAGE', 10));
        $page = $request->input('page', env('DATA_PER_PAGE', 10));

        if ($page === 'all') {
            $data = $query->get();
        } else {
            $data = $query->paginate($perPage);
        }
        return LampiranSuratKeluarResource::collection($data);
    }

    //OKE 
    public function findID($id)
    {
        $data = LampiranSuratKeluar::findOrFail($id);
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
    public function store(LampiranSuratKeluarRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $data = LampiranSuratKeluar::create($validatedData);

            // $data['tanggal']
            return response()->json([
                'success' => true,
                'message' => 'created successfully',
                'data' => new LampiranSuratKeluarResource($data),
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

    // public function store(LampiranSuratKeluarRequest $request)
    // {
    //     try {
    //         return DB::transaction(function () use ($request) {
    //             $validatedData = $request->validated();

    //             $uploadedFile = $request->file('lampiran');
    //             $originalFileName = $uploadedFile->getClientOriginalName();
    //             $ukuranFile = $uploadedFile->getSize();
    //             $tipeFile = $uploadedFile->getMimeType();

    //             $storagePath = 'uploads/' . date('Y/m/d');

    //             if (!File::isDirectory(public_path($storagePath))) {
    //                 File::makeDirectory(public_path($storagePath), 0755, true);
    //             }

    //             $fileName = \WebApp::generateUniqueFileName($originalFileName);
    //             $uploadedFile->move(public_path($storagePath), $fileName);

    //             $upload = new Upload([
    //                 'path' => $storagePath . '/' . $fileName,
    //                 'name' => $originalFileName,
    //                 'size' => $ukuranFile,
    //                 'type' => $tipeFile,
    //                 'user_id' => $request->input('user_id'),
    //             ]);
    //             $upload->save();

    //             $lampiranSuratKeluar = new LampiranSuratKeluar($validatedData);
    //             $lampiranSuratKeluar->upload_id = $upload->id;
    //             $lampiranSuratKeluar->surat_masuk_id = $request->input('surat_masuk_id');
    //             $lampiranSuratKeluar->save();

    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'uploaded successfully',
    //                 'data' => new LampiranSuratKeluarResource($lampiranSuratKeluar),
    //             ], 201);
    //         });
    //     } catch (\Throwable $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to upload',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }



    //OKE PUT application/x-www-form-urlencoded
    public function update(LampiranSuratKeluarRequest $request, $id)
    {

        try {
            $validatedData = $request->validated();
            $data = $this->findId($id);
            $data->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'updated successfully',
                'data' => new LampiranSuratKeluarResource($data),
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
            $upload_id = $data->upload_id;
            $data->delete();

            //cari file
            $data = UploadController::findId($upload_id);
            File::delete(public_path($data->path));
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
