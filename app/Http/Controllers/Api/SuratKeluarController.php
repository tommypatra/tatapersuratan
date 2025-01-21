<?php

namespace App\Http\Controllers\Api;

use App\Models\AksesPola;
use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\SuratKeluarRequest;
use App\Http\Resources\SuratKeluarResource;
use Illuminate\Validation\ValidationException;

class SuratKeluarController extends Controller
{
    //OKE
    public function index(Request $request)
    {

        $user_id = auth()->user()->id;


        $query = SuratKeluar::orderBy('tanggal', 'desc')
            ->orderBy('no_indeks', 'desc')
            ->orderBy('no_sub_indeks', 'desc')
            ->orderBy('perihal', 'asc')
            ->with([
                'klasifikasiSurat',
                'user',
                'lampiranSuratKeluar.upload',
                'polaSpesimen.polaSurat',
                'polaSpesimen.spesimenJabatan',
                'distribusi.user' => function ($query) {
                    $query->select('id', 'name', 'email');
                },
            ]);

        // if (!izinkanAkses("admin")) {
        //     $query->where('user_id', auth()->user()->id);
        // }

        $filter = $request->input('filter');
        if ($filter) {
            $filterArray = json_decode($filter, true);
            if (is_array($filterArray)) {
                foreach ($filterArray as $i => $dp) {
                    if ($i == 'kategori')
                        switch ($dp) {
                            case "konsep":
                                $query->where('is_diajukan', '!=', 1);
                                break;
                            case "diajukan":
                                $query->where('is_diajukan', 1)->whereNull('is_diterima');
                                break;
                            case "diterima":
                                $query->where('is_diajukan', 1)->where('is_diterima', 1);
                                break;
                            case "ditolak":
                                $query->where('is_diajukan', 1)->where('is_diterima', 0);
                                break;
                        }
                    elseif ($i == 'tahun') {
                        $tahun_sekarang = $dp;
                        $aksespola = getAksesPola($user_id, $tahun_sekarang);
                        // dd($aksespola['data']);
                        $query->whereYear('tanggal', $tahun_sekarang);
                        if (!empty($aksespola['data'])) {
                            $idAkses = $aksespola['data']['pola_spesimen_id'];
                            // dd($idAkses);
                            $query->where(function ($query) use ($user_id, $idAkses) {
                                $query->orWhere('user_id', $user_id);
                                $query->orWhere(function ($query) use ($idAkses) {
                                    $query->whereIn('pola_spesimen_id', $idAkses);
                                });
                            });
                        } else {
                            // $rolesAkun = $request->input('roles_akun');
                            if (!izinkanAkses("admin")) {
                                $query->where('user_id', auth()->user()->id);
                            }
                        }
                    } elseif ($i == 'bulan') {
                        $bulan_sekarang = $dp;
                        $query->whereMonth('tanggal', $bulan_sekarang);
                    } else {
                        $query->where($i, $dp);
                    }
                }
            }
        }


        // if (!izinkanAkses("admin")) {
        //     $query->where('user_id', auth()->user()->id);
        // }

        //untuk pencarian
        $keyword = $request->input('keyword');
        if ($keyword) {
            $query->where(function ($query) use ($keyword) {
                $query->where('perihal', 'LIKE', "%$keyword%")
                    ->orWhere('no_surat', 'LIKE', "%$keyword%")
                    ->orWhere('ringkasan', 'LIKE', "%$keyword%")
                    ->orWhere('asal', 'LIKE', "%$keyword%");
            });
        }

        $sql = $query->toSql();
        $bindings = $query->getBindings();
        // dd($sql);

        $perPage = $request->input('pet_page', env('DATA_PER_PAGE', 10));
        $page = ($perPage == 'all') ? 'all' : $request->input('page', env('DATA_PER_PAGE', 10));


        if ($page === 'all') {
            $data = $query->get();
        } else {
            $perPage = ($perPage == 'all') ? 20 : 20;

            $data = $query->paginate($perPage);
        }

        return SuratKeluarResource::collection($data);
    }

    //OKE 
    public function findID($id)
    {
        $data = SuratKeluar::with(['klasifikasiSurat'])->findOrFail($id);
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

    public function cekAksesPola($tanggal_surat = null, $pola_spesimen_id = null)
    {
        $tanggal_surat = $tanggal_surat ?? date('Y-m-d');
        $timestamp = strtotime($tanggal_surat);
        if (!$timestamp || !$pola_spesimen_id) {
            return false;
        }
        $tahun = date('Y', $timestamp);
        $cek = AksesPola::where('user_id', auth()->user()->id)
            ->where('tahun', $tahun)
            ->where('pola_spesimen_id', $pola_spesimen_id)
            ->exists();

        return $cek;
    }

    //OKE PUT application/x-www-form-urlencoded
    public function store(SuratKeluarRequest $request)
    {
        try {

            $validatedData = $request->validated();
            $validatedData['user_id'] = auth()->user()->id;
            $gabungkan = isset($request['gabungkan']) ? true : false;
            $tujuan = $this->parseTujuan($validatedData['tujuan']);
            $perihal = $validatedData['perihal'];
            $pesan = [];
            $responseData = [];
            // dd($tujuan);
            $jumlah_tujuan = count($tujuan);
            foreach ($tujuan as $i => $dp) {
                if ($gabungkan)
                    $validatedData['perihal'] = $perihal . " " . $dp;
                $validatedData['is_diajukan'] = 1;
                $data = SuratKeluar::create($validatedData);

                // jika ada akses maka generatekan nomor suratnya
                $tmppesan = 'Pengajuan nomor surat <i>' . $data->perihal . '</i> tanggal ' . $data->tanggal;
                if ($this->cekAksesPola($data->tanggal, $data->pola_spesimen_id)) {
                    $generateValue = $this->updateNoSurat($data->id, $data);
                    $dataSave = [
                        'is_diterima' => 1,
                        'is_diajukan' => 1,
                        'catatan' => null,
                        'verifikator' => auth()->user()->name,
                        'no_surat' => $generateValue['no_surat'],
                        'no_indeks' => $generateValue['no_indeks'],
                        'no_sub_indeks' => $generateValue['no_sub_indeks'],
                        'pola' => $generateValue['pola'],
                    ];
                    $tmppesan .= '  dengan nomor <b>' . $generateValue['no_surat'] . "</b>";
                    $data->update($dataSave);
                }
                $tmppesan .= '  berhasil dilakukan.';
                $pesan[] = $tmppesan;

                $responseData[] = new SuratKeluarResource($data);
            }


            // dd($data);
            $data_admin = getAdminSpesimen($validatedData['pola_spesimen_id']);
            foreach ($data_admin['data'] as $i => $row) {
                // if ($row->user->profil->hp) {
                if ($row->user->profil->hp && $row->user->id != auth()->user()->id) {
                    $pesanWA = "Hai " . $row->user->name . ", beberapa saat lalu " . auth()->user()->name . " mengajukan pengambilan nomor, yaitu :\n";
                    foreach ($pesan as $i => $item) {
                        $pesanWA .= "\n" . $item . "\n";
                    }
                    $pesanWA .= "\nsilahkan cek dengan login laman https://surat.iainkendari.ac.id/";
                    kirimWA($row->user->profil->hp, $pesanWA);
                }
            }

            $kirimpesan = "";
            foreach ($pesan as $i => $item) {
                $kirimpesan .= "<p>" . $item . "</p>";
            }


            // $data['tanggal']
            return response()->json([
                'success' => true,
                'message' => '<div id="salinText" onclick="salinText()">' . $kirimpesan . '</div>',
                'data' => $responseData,
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

    private function parseTujuan($tujuan = null)
    {
        $result = [""];
        if ($tujuan) {
            $result = [];
            // Ganti newline (\n) dengan koma untuk konsistensi pemrosesan
            $tujuan = preg_replace('/\s*\n\s*/', ',', $tujuan);

            // Pola untuk memisahkan berdasarkan angka dan titik, atau hanya koma
            $pattern = '/\s*,\s*(?=\d+\.)|\s*,\s*/';

            // Pisahkan data berdasarkan pola
            $split = preg_split($pattern, trim($tujuan));

            foreach ($split as $item) {
                // Jika ada angka dan titik, hapus mereka; jika tidak, tetap gunakan
                $cleaned = preg_replace('/^\d+\.\s*/', '', $item);
                $result[] = trim($cleaned); // Hilangkan spasi
            }
        }
        return $result;
    }


    //OKE PUT application/x-www-form-urlencoded
    public function update(SuratKeluarRequest $request, $id)
    {

        try {
            $validatedData = $request->validated();
            $validatedData['user_id'] = auth()->user()->id;

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
            ], 204);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function ajukan(Request $request)
    {
        try {
            $data = $this->findId($request->input('id'));
            if ($data->is_diajukan)
                return response()->json([
                    'success' => false,
                    'message' => 'pengajuan gagal',
                    'error' => 'sudah diajukan',
                ], 500);

            $data->update(['is_diajukan' => 1]);

            return response()->json([
                'success' => true,
                'message' => 'pengajuan successfully',
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

    public function updateNoSurat($id = null, $data = null)
    {
        if (!$data) {
            $data = $this->findId($id);
        }

        // dd($data);
        $id = $data->id;
        $perihal = $data->perihal;
        $tanggal = $data->tanggal;
        $no_indeks = $data->no_indeks;
        $no_sub_indeks = $data->no_sub_indeks;
        $klasifikasi_surat_id = $data->klasifikasi_surat_id;
        $pola_spesimen_id = $data->pola_spesimen_id;

        $generateValue = generateNomorKeluar($tanggal, $pola_spesimen_id, $klasifikasi_surat_id, $no_indeks, $no_sub_indeks, null);
        return $generateValue;
    }

    public function prosesAjuan(Request $request)
    {
        try {
            $dataSave = [
                'is_diterima' => $request->input('is_diterima'),
                'catatan' => $request->input('catatan'),
                'verifikator' => auth()->user()->name,
            ];
            $data = $this->findId($request->input('id'));


            $identitas = getIdentitasUser($data->user_id);
            $pesanWA = "Hai, " . $identitas['data']->name . " pengajuan nomor surat anda telah diperiksa.\n\n";

            //untuk filter siapa yang bisa setujui atau tolak ajuan surat
            $daftarAksesPola = getAksesPola(auth()->user()->id, substr($data->tanggal, 0, 4));
            if (!in_array($data->pola_spesimen_id, $daftarAksesPola['data']['pola_spesimen_id'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal dilakukan',
                    'error' => ['tidak ada akses'],
                ], 500);
            }

            $pesan = 'Pengajuan surat perihal <i>' . $data->perihal . '</i> tanggal <i>' . $data->tanggal;
            if ($request->input('is_diterima')) {
                $generateValue = $this->updateNoSurat($data->id, $data);
                $dataSave += [
                    'no_surat' => $generateValue['no_surat'],
                    'no_indeks' => $generateValue['no_indeks'],
                    'no_sub_indeks' => $generateValue['no_sub_indeks'],
                    'pola' => $generateValue['pola'],
                ];
                $pesan .=  '</i> berhasil, dengan nomor <b>' . $generateValue['no_surat'] . '</b>';
            } else {
                $pesan = ($request->input('catatan')) ? $pesan . ' tidak diterima karena ' . $request->input('catatan') : 'tidak diterima';
            }
            $data->update($dataSave);

            $pesanWA .= $pesan;
            $pesanWA .= "\n\nsilahkan cek dengan login laman https://surat.iainkendari.ac.id/";

            if ($identitas['data']->profil->hp) {
                kirimWA($identitas['data']->profil->hp, $pesanWA);
            }

            return response()->json([
                'success' => true,
                'message' => '<div id="salinText" onclick="salinText()">' . $pesan . '</div>',
                'data' => $dataSave,
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
