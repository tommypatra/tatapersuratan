<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\UploadRequest;
use App\Http\Resources\UploadResource;
use App\Models\Upload;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class UploadController extends Controller
{
    //OKE
    public function index(Request $request)
    {
        $query = Upload::orderBy('tanggal', 'desc')
            ->with(['user', 'lampiranSuratMasuk']);

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
            $perPage = ($perPage == 'all') ? 20 : 20;

            $data = $query->paginate($perPage)->onEachSide(1);
        }
        return UploadResource::collection($data);
    }

    //OKE 
    public static function findID($id)
    {
        $data = Upload::findOrFail($id);
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

    public function store(UploadRequest $request)
    {
        try {

            if (!$request->hasFile('file')) {
                throw new \Exception('File tidak ditemukan');
            }

            $uploadedFile = $request->file('file');

            // pastikan upload valid
            if (!$uploadedFile->isValid()) {
                throw new \Exception('Upload file gagal');
            }

            // informasi file
            $originalName = $uploadedFile->getClientOriginalName();
            $size = $uploadedFile->getSize();
            $mime = $uploadedFile->getMimeType();
            $ext  = strtolower($uploadedFile->extension());

            // whitelist extension
            $allowedExt = [
                'pdf',
                'doc',
                'docx',
                'xls',
                'xlsx',
                'jpg',
                'jpeg',
                'png'
            ];

            if (!in_array($ext, $allowedExt)) {
                throw new \Exception('Tipe file tidak diizinkan');
            }

            // blacklist tambahan
            $blocked = [
                'php',
                'phtml',
                'php3',
                'php4',
                'php5',
                'phar',
                'shtml',
                'cgi',
                'pl',
                'exe',
                'sh'
            ];

            if (in_array($ext, $blocked)) {
                throw new \Exception('File berbahaya terdeteksi');
            }

            // batasi ukuran (5MB)
            $maxSize = 5 * 1024 * 1024;
            if ($size > $maxSize) {
                throw new \Exception('Ukuran file terlalu besar');
            }

            // path penyimpanan
            $storagePath = 'uploads/' . date('Y/m/d');

            if (!File::isDirectory(public_path($storagePath))) {
                File::makeDirectory(public_path($storagePath), 0755, true);
            }

            // nama file random (tidak pakai nama user)
            $fileName = Str::random(40) . '.' . $ext;

            // pastikan tidak ada collision
            while (File::exists(public_path($storagePath . '/' . $fileName))) {
                $fileName = Str::random(40) . '.' . $ext;
            }

            // simpan file
            $uploadedFile->move(public_path($storagePath), $fileName);

            // simpan database
            $data = Upload::create([
                'path' => $storagePath . '/' . $fileName,
                'name' => basename($originalName),
                'size' => $size,
                'type' => $mime,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'created successfully',
                'data' => new UploadResource($data),
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
                'message' => 'Upload gagal',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    //OKE PUT application/x-www-form-urlencoded
    public function update(UploadRequest $request, $id)
    {

        try {
            $validatedData = $request->validated();
            $validatedData['user_id'] = auth()->user()->id;
            $data = $this->findId($id);

            $data->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'updated successfully',
                'data' => new UploadResource($data),
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
