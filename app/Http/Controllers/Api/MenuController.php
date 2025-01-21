<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function getMenu($id)
    {
        $menu = [];
        if ($id == 1) {
            $menu = [
                ['label' => 'Grup', 'route' => 'grup', 'icon' => 'users'],
                ['label' => 'Akun', 'route' => 'user-app', 'icon' => 'user'],
                ['label' => 'Kategori Surat Masuk', 'route' => 'kategori-surat-masuk', 'icon' => 'server'],
                ['label' => 'Pola', 'route' => 'pola-surat-keluar', 'icon' => 'feather'],
                ['label' => 'Klasifikasi', 'route' => 'klasifikasi-surat-keluar', 'icon' => 'briefcase'],
                ['label' => 'Spesimen', 'route' => 'spesimen-jabatan', 'icon' => 'layers'],
                ['label' => 'Pola Spesimen', 'route' => 'pola-spesimen', 'icon' => 'settings'],
                ['label' => 'Akses Surat Keluar', 'route' => 'akses-pola', 'icon' => 'tool'],
                ['label' => 'Akses Disposisi', 'route' => 'akses-disposisi', 'icon' => 'tool'],
            ];
        }

        $respon_data = [
            'success' => true,
            'message' => 'menu akses akun',
            'data' => $menu,
        ];
        return response()->json($respon_data, 200);
    }
}
