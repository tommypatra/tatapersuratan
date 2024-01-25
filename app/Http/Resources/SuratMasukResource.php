<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class SuratMasukResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'tanggal' => $this->tanggal,
            'no_agenda' => $this->no_agenda,
            'no_surat' => $this->no_surat,
            'perihal' => $this->perihal,
            'asal' => $this->asal,
            'tempat' => $this->tempat,
            'type' => 'Surat Masuk',

            'is_diterima' => $this->is_diterima,
            'is_diajukan' => $this->is_diajukan,
            'catatan' => $this->catatan,
            'verifikator' => $this->verifikator,

            'kategori_surat_masuk_id' => $this->kategori_surat_masuk_id,
            'user_id' => $this->user_id,

            'kategori_surat_masuk' => new KategoriSuratMasukResource($this->kategoriSuratMasuk),

            'user' => new UserAppResource($this->user),
            'lampiran_surat_masuk' => LampiranSuratMasukResource::collection($this->lampiranSuratMasuk),
            'tujuan' => TujuanResource::collection($this->tujuan),

            // 'created_at' => $this->created_at,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),

            'jumlah_lampiran' => LampiranSuratMasukResource::collection($this->lampiranSuratMasuk)->count(),


        ];
        // return parent::toArray($request);
    }
}
