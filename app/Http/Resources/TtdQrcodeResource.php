<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use App\Http\Resources\UserAppResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\KategoriSuratMasukResource;

class TtdQrcodeResource extends JsonResource
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
            'kode' => $this->kode,
            'tanggal' => $this->tanggal,
            'no_surat' => $this->no_surat,
            'perihal' => $this->perihal,
            'pejabat' => $this->pejabat,
            'jabatan' => $this->jabatan,
            'qrcode' => $this->qrcode,
            'file' => $this->file,
            'is_diajukan' => $this->is_diajukan,
            'is_diterima' => $this->is_diterima,
            'catatan' => formatNotNull($this->catatan),

            'user_ttd_id' => $this->user_ttd_id,
            'user_id' => $this->user_id,

            'user' => new UserAppResource($this->user),
            'tujuan' => new UserAppResource($this->ttd),
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),

        ];
        // return parent::toArray($request);
    }
}
