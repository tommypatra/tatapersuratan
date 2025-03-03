<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Profil;
use App\Models\GrupUser;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\AuthRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\AuthResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(AuthRequest $request)
    {

        try {
            $credentials = $request->validated();

            if (!$token = auth()->guard('api')->attempt($credentials)) {
                // if (!$token = auth()->guard('api')->claims([
                //     'email' => $request->input('email')
                // ])->attempt($credentials)) {
                return response()->json([
                    'status' => false,
                    'data'    => null,
                    'message'   => "login gagal"
                ], 401);
            }

            $user = auth()->guard('api')->user();
            // dd($user);

            $profil = Profil::where("user_id", $user->id)->first();

            // $user = new AuthResource($user);
            $foto = 'foto/user-avatar.png';
            if ($user->profil) {
                $foto = ($user->profil->foto) ? ($user->profil->foto) : 'foto/user-avatar.png';
            }
            $daftarAksesData = $this->daftarAkses($request->input('email'))->getData();

            $hakakses = $daftarAksesData->data->hakakses;
            // dd($daftarAksesData->data->grup);

            $akses = $daftarAksesData->data->akses;
            // dd($daftarAksesData->data->grup);
            $respon_data = [
                'success' => true,
                'message' => 'user ditemukan',
                'data' => [
                    'akun' => $user,
                    'foto' => $foto,
                    'access_token' => $token,
                    'hakakses' => $hakakses,
                    'akses' => $akses,
                ]
            ];
            return response()->json($respon_data, 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    // public function login(AuthRequest $request)
    // {

    //     try {
    //         $credentials = $request->validated();
    //         if (Auth::attempt($credentials)) {
    //             $user = User::where('email', $request->email)->with(['profil'])->first();
    //             // $user = new AuthResource($user);
    //             $foto = ($user->profil->foto) ? $user->profil->foto : 'foto/user-avatar.png';
    //             $daftarAksesData = $this->daftarAkses($request)->getData();
    //             $hakakses = $daftarAksesData->data->hakakses;
    //             // dd($daftarAksesData->data->grup);

    //             $token = $user->createToken('api_token', $daftarAksesData->data->grup)->plainTextToken;

    //             $akses = $daftarAksesData->data->akses;
    //             // dd($daftarAksesData->data->grup);
    //             $respon_data = [
    //                 'success' => true,
    //                 'message' => 'user ditemukan',
    //                 'data' => $user,
    //                 'foto' => $foto,
    //                 'access_token' => $token,
    //                 'hakakses' => $hakakses,
    //                 'akses' => $akses,
    //                 'token_type' => 'Bearer',
    //             ];
    //             return response()->json($respon_data, 200);
    //         }
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'User atau password anda salah',
    //         ], 401);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Something went wrong. Please try again later.',
    //         ], 500);
    //     }
    // }


    public function daftarAkses($email)
    {
        try {
            $getAkses = User::with('grupUser.grup')->where('email', $email);
            if (!$getAkses->first()) {
                return response()->json([
                    'success' => false,
                    'message' => 'tidak ditemukan',
                ], 404);
            }
            $dtAkses = $getAkses->first();
            $akses = null;
            $grup = [];
            foreach ($dtAkses->grupUser as $i => $grp) {
                $grup[] = $grp->grup->grup;
                if (!$akses)
                    $akses = $grp->grup_id;
            }
            $hakakses = $dtAkses->grupUser;
            $respon_data = [
                'success' => true,
                'message' => 'ditemukan',
                'data' => [
                    'hakakses' => $hakakses,
                    'grup' => $grup,
                    'akses' => $akses,
                ]
            ];
            return response()->json($respon_data, 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('akun-masuk');
    }

    public function simpanPendaftaran(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
                'jenis_kelamin' => 'required',
                'nip' => 'required',
                'alamat' => 'nullable',
                'hp' => 'required|numeric',
            ]);

            $dtUser = [
                'name' => $validatedData['name'],
                'password' => Hash::make($validatedData['password']),
                'email' => $validatedData['email'],
            ];
            $data = User::create($dtUser);

            $dtGrup = [
                'user_id' => $data->id,
                'grup_id' => 2,
            ];
            $grup = GrupUser::create($dtGrup);

            $dtProfil = [
                'user_id' => $data->id,
                'nip' => $validatedData['nip'],
                'alamat' => $validatedData['alamat'],
                'hp' => $validatedData['hp'],
                'jenis_kelamin' => $validatedData['jenis_kelamin'],
            ];
            $profil = Profil::create($dtProfil);

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil, silahkan login dan memperbaharui data profil anda',
                'data' => $data,
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

    public function refreshToken()
    {
        try {
            $newToken = auth()->refresh(); // Generate token baru
            return response()->json([
                'access_token' => $newToken,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Token tidak bisa diperbarui'], 401);
        }
    }


    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $email = $googleUser->getEmail();
            $user = User::with('profil')->where('email', $email)->first();
            if (!$user)
                return redirect::to('login')->with('error', 'Login gagal, ' . $email . ' belum terdaftar');

            // Auth::login($user);

            $token = JWTAuth::fromUser($user);

            if (!$token) {
                return redirect()->to('login')->with('error', 'Tidak bisa membuat token.');
            }
            $profil = Profil::where("user_id", $user->id)->first();

            $foto = 'foto/user-avatar.png';
            if ($user->profil) {
                $foto = ($user->profil->foto) ? ($user->profil->foto) : 'foto/user-avatar.png';
            }
            $daftarAksesData = $this->daftarAkses($email)->getData();
            $hakakses = $daftarAksesData->data->hakakses;
            $akses = $daftarAksesData->data->akses;
            $respon_data = [
                'success' => true,
                'message' => 'user ditemukan',
                'data' => [
                    'akun' => $user,
                    'foto' => $foto,
                    'access_token' => $token,
                    'hakakses' => $hakakses,
                    'akses' => $akses,
                ]
            ];
            // dd($respon_data);
            return redirect::to('/login')->with('respon_google_login', $respon_data);
        } catch (\Exception $e) {
            return Redirect::to('login')->with('error', 'Login failed, please try again. ' . $e->getMessage());
        }
    }
}
