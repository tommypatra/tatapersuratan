<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class AksesDisposisiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'tahun' => $this->tahun,
            'spesimen_jabatan_id' => $this->spesimen_jabatan_id,
            'spesimen_jabatan' => $this->spesimenJabatan,
            'user_id' => $this->user_id,
            'user' => $this->user,
            // 'pola_surat' => $this->polaSpesimen->polaSurat,
            // 'spesimen_jabatan' => $this->polaSpesimen->spesimenJabatan,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::parse($this->updated_at)->format('Y-m-d H:i:s'),
        ];

        // return parent::toArray($request);
    }
}
