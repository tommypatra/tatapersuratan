<?php

namespace App\Http\Controllers\Api;

use App\Models\TtdQrcode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TtdQrcodeRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Resources\TtdQrcodeResource;
use setasign\Fpdi\Fpdi;
use Smalot\PdfParser\Parser;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Element\Image;

class TtdQrcodeController extends Controller
{
    //OKE
    public function index(Request $request)
    {
        $query = TtdQrcode::orderBy('is_diterima', 'asc')
            ->orderBy('tanggal', 'asc')
            ->orderBy('perihal', 'asc')
            ->with([
                'user', 'ttd'
            ]);
        $rolesAkun = $request->input('roles_akun');

        $filter = $request->input('filter');
        if ($filter) {
            $filterArray = json_decode($filter, true);
            if (is_array($filterArray)) {
                foreach ($filterArray as $i => $dp) {
                    $query->where($i, $dp);
                }
            } else {
                if ($filter == 'masuk') {
                    $query->where(function ($query) {
                        $query->where('user_id', auth()->user()->id)
                            ->orWhere('user_ttd_id', auth()->user()->id);
                    })
                        ->whereNull('is_diterima');
                } elseif ($filter == 'diterima') {
                    $query->where(function ($query) {
                        $query->where('user_id', auth()->user()->id)
                            ->orWhere('user_ttd_id', auth()->user()->id);
                    })
                        ->where('is_diterima', 1);
                } elseif ($filter == 'ditolak') {
                    $query->where(function ($query) {
                        $query->where('user_id', auth()->user()->id)
                            ->orWhere('user_ttd_id', auth()->user()->id);
                    })
                        ->where('is_diterima', 0);
                }
            }
        }

        //untuk pencarian
        $keyword = $request->input('keyword');
        if ($keyword) {
            $query->where('perihal', 'LIKE', "%$keyword%")
                ->orWhere('no_surat', 'LIKE', "%$keyword%")
                ->orWhere('tanggal', 'LIKE', "%$keyword%");
            // ->orWhere('asal', 'LIKE', "%$keyword%");
        }

        $perPage = $request->input('per_page', env('DATA_PER_PAGE', 10));
        if ($perPage === 'all') {
            $data = $query->get();
        } else {
            $data = $query->paginate($perPage);
        }
        return TtdQrcodeResource::collection($data);
    }

    //OKE 
    public function findID($id)
    {
        $data = TtdQrcode::with([
            'user', 'ttd'
        ])->findOrFail($id);
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
    public function store(TtdQrcodeRequest $request)
    {
        try {
            $validatedData = $request->validated();
            if ($request->hasFile('file')) {
                $validatedData['file'] = uploadFile($request);
            }
            $data = TtdQrcode::create($validatedData);

            // ini untuk update kode sesuai id
            $id = $data->id;
            $cariData = $this->findId($id);
            $updateData['kode'] = generateKode($id);
            $cariData->update($updateData);

            // $data['tanggal']
            return response()->json([
                'success' => true,
                'message' => 'created successfully',
                'data' => new TtdQrcodeResource($data),
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
    public function update(TtdQrcodeRequest $request, $id)
    {

        try {
            $validatedData = $request->validated();
            $data = $this->findId($id);

            if ($request->hasFile('file')) {
                // if ($data->file)
                File::delete(public_path($data->file));
                $validatedData['file'] = uploadFile($request);
            }

            if (!$data->kode) {
                $validatedData['kode'] = generateKode($id);
            }

            $data->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'updated successfully',
                'data' => new TtdQrcodeResource($data),
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
            File::delete(public_path($data->file_upload));

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

    public function verifikasi(Request $request, $id)
    {
        try {
            // $pdfInfo = json_decode($request->input('pdf'), true);
            $request->validate([
                'is_diterima' => 'required',
                'catatan' => 'nullable',
            ]);

            $data = $this->findId($id);
            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Tidak Ditemukan',
                    'errors' => 'data dengan id terseubt tidak ditemukan',
                ], 200);
            } elseif ($data->user_ttd_id != auth()->user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses Ditolak',
                    'errors' => 'anda tidak diperbolehkan mengakses layanan ini',
                ], 200);
            }

            $dataUpdate = [
                'is_diterima' => $request->input('is_diterima'),
                'catatan' => $request->input('catatan'),
            ];

            if ($request->input('is_diterima') == 1) {
                $file = public_path($data->file);
                $pathqr = generateQrCode($data);
                $dataUpdate['qrcode'] = $pathqr;
                $file = str_replace('/', DIRECTORY_SEPARATOR, $file); // Menyesuaikan separator path
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                $this->updatePdfQrCode($data, $pathqr);
                // if (strtolower($ext) == 'pdf') {
                //     $this->updatePdfQrCode($data,$pathqr);
                // } else {
                //     $this->updateDocQrCode($data,$pathqr);
                // }
            }

            $data->update($dataUpdate);

            return response()->json([
                'success' => true,
                'message' => 'updated successfully',
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update verification',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    function updateDocQrCode($data, $pathqr)
    {
        // $pathqr = generateQrCode($data);
        $filePath = public_path($data->file);

        // Muat dokumen Word
        $phpWord = IOFactory::load($filePath);
        foreach ($phpWord->getSections() as $section) {
            $footer = $section->addFooter();
            $footer->addImage($pathqr, ['width' => 65, 'height' => 65]);
            $footer->addText('test', ['left' => 70]);
        }

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($filePath);
        // File::delete($pathqr);

        convertDocToPDF($filePath);
    }

    public function convertDocToPDF($path)
    {
        $directory = dirname($path);
        $filename = pathinfo($path, PATHINFO_FILENAME);
        $file = $directory . '/' . $filename . '.pdf';

        $domPdfPath = base_path('vendor/dompdf/dompdf');
        \PhpOffice\PhpWord\Settings::setPdfRendererPath($domPdfPath);
        \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');
        $Content = \PhpOffice\PhpWord\IOFactory::load(public_path($path));
        $PDFWriter = \PhpOffice\PhpWord\IOFactory::createWriter($Content, 'PDF');
        $PDFWriter->save(public_path($file));
        File::delete($path);
        return $file;
    }

    function updatePdfQrCode($data, $pathqr)
    {
        // $pathqr = generateQrCode($data);
        $filePath = public_path($data->file);

        // ---------- class fpdi -------------
        $fpdi = new FPDI;
        $count = $fpdi->setSourceFile($filePath);
        for ($i = 1; $i <= $count; $i++) {

            $template = $fpdi->importPage($i);
            $size = $fpdi->getTemplateSize($template);

            $fpdi->AddPage($size['orientation'], array($size['width'], $size['height']));
            $fpdi->useTemplate($template);

            $fpdi->SetFont("helvetica", "I", 10);
            $fpdi->SetTextColor(153, 0, 153);
            $text = "dokumen tervalidasi menggunakan ttd elektronik persuratan IAIN Kendari";
            $fpdi->Text(10, 10, $text);

            $left = $size['width'];
            $top = $size['height'];

            $fpdi->Image($pathqr, 10, ($top - 25));
            // $fpdi->SetFont("helvetica", "I", 10);
            $fpdi->SetTextColor(0, 0, 0);
            $fpdi->Text(30, ($top - 19), "dokumen ini sah dan ditandatangani secara elektronik oleh " . $data->pejabat);
            $fpdi->Text(30, ($top - 15), "selaku " . $data->jabatan . " yang diterbitkan oleh layanan persuratan digital");
            $fpdi->Text(30, ($top - 11), "https://surat.iainkendari.ac.id TIPD@" . date('Y'));
        }

        $fpdi->Output($filePath, 'F');
        // File::delete($pathqr);
        // echo "SUKSES PDF";
        // die;
    }
}
