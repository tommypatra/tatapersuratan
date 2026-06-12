<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Jobs\SendMessageJob;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{

    public function kirim(Request $request)
    {
        $request->validate([
            'pesan' => 'required|string',
            'judul' => 'nullable|string',
            'hp'  => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        if(empty($request->hp) && empty($request->email))
        {
            return response()->json([
                'status' => false,
                'pesan' => 'No HP atau Email harus diisi'
            ], 422);
        }

        if(!empty($request->hp))
        {
            SendMessageJob::dispatch(
                $request->hp,
                $request->pesan
            );
        }

        if(!empty($request->email))
        {
            SendEmailJob::dispatch(
                $request->email,
                $request->judul ?? 'Notifikasi',
                $request->pesan
            );
        }

        return response()->json([
            'status' => true,
            'pesan' => 'Notifikasi masuk antrian'
        ]);
    }
}
