<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class TujuanResource extends JsonResource
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
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'user_id' => $this->user_id,
            'waktu_akses' => $this->waktu_akses,
            'surat_masuk_id' => $this->surat_masuk_id,
            'user' => $this->user,
            'surat_masuk' => $this->suratMasuk,
            'disposisi' => $this->disposisi,
        ];
        // return parent::toArray($request);
    }
}
