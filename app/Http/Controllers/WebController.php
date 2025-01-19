<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class WebController extends Controller
{

    public function dashboard()
    {
        // dd(auth()->user());
        // echo auth()->user()->id . " " . auth()->user()->name . " " . auth()->user()->email;
        // dd(session()->all());
        // dd(session()->get("hakakses"));   

        return view('admin.dashboard');
    }

    public function setSession(Request $request)
    {
        $user = User::where('email', $request->input('email'))->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'tidak ditemukan',
            ], 404);
        }
        //set auth session login untuk middleware
        Auth::login($user);
        //set session
        session()->put('access_token', $request->input('access_token'));
        session()->put('akses', $request->input('akses'));
        session()->put('hakakses', $request->input('hakakses'));
        session()->put('foto', $request->input('foto'));

        $respon_data = [
            'success' => true,
            'message' => 'set berhasil ditemukan',
        ];
        $respon_data['hakakses'] = session()->get("hakakses");
        $respon_data['hakakses_html'] = listAksesHtml(session()->get("hakakses"));

        return response()->json($respon_data, 200);
    }

    public function setAkses($grup_id = null)
    {
        $hakakses = session()->get("hakakses");
        $boleh = false;
        foreach ($hakakses as $i => $dp) {
            if ($dp['grup_id'] == $grup_id) {
                $boleh = true;
            }
        }
        if ($boleh) {
            session(
                [
                    'akses' => $grup_id,
                ]
            );
        }
        return redirect()->route('akun-dashboard');
    }

    public function daftarAkses()
    {
        $hakakses = session()->get("hakakses");
        // dd($hakakses);
        $respon_data['hakakses'] = $hakakses;
        $respon_data['hakakses_html'] = listAksesHtml($hakakses);
        return response()->json($respon_data, 200);
    }

    public function masuk()
    {
        return view('masuk');
    }

    public function mendaftar()
    {
        return view('daftar');
    }

    public function suratMasuk()
    {
        return view('admin.surat_masuk');
    }

    public function kategoriSuratMasuk()
    {
        return view('admin.kategori_surat_masuk');
    }

    public function userApp()
    {
        return view('admin.user');
    }

    public function polaSuratKeluar()
    {
        return view('admin.pola_surat_keluar');
    }

    public function klasifikasiSuratKeluar()
    {
        return view('admin.klasifikasi_surat_keluar');
    }

    public function spesimenJabatan()
    {
        return view('admin.spesimen_jabatan');
    }

    public function grup()
    {
        return view('admin.grup');
    }

    public function aksesSpesimen()
    {
        return view('admin.akses_spesimen');
    }

    public function aksesPola()
    {
        return view('admin.akses_pola');
    }

    public function aksesSuratMasuk()
    {
        return view('admin.akses_surat_masuk');
    }

    public function profil()
    {
        return view('admin.profil');
    }

    public function disposisi()
    {
        return view('admin.disposisi');
    }

    public function disposisiDetail($id = null)
    {
        return view('admin.disposisi_detail', ['id' => $id]);
    }

    public function suratKeluar()
    {
        return view('admin.surat_keluar');
    }

    public function distribusi()
    {
        return view('admin.distribusi');
    }

    public function suratKeluarDetail($id = null)
    {
        return view('admin.surat_keluar_detail', ['id' => $id]);
    }

    public function ttdElektronik()
    {
        return view('admin.ttd_qrcode');
    }

    public function polaSpesimen()
    {
        return view('admin.pola_spesimen');
    }

    public function detailTte($kode = null)
    {
        return view('tte', ['kode' => $kode]);
    }

    public function cekLoginEksternal($web = null)
    {
        switch ($web) {
            case 'simpeg':
                $url = "https://simpeg.iainkendari.ac.id/api/cek-status-login";
                break;
            default:
                return redirect()->route('login')->with('error', 'Invalid external login web type.');
                break;
        }
        return view('auto_login', ['url' => $url]);
    }

    public function cetakLembarDisposisi($id = null)
    {
        return view('cetak/lembar_disposisi', ['id' => $id]);
    }

    public function scanQrCode()
    {
        return view('admin/scan_qrcode');
    }
}
