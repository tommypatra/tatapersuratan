<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;


class AksesPolaResource extends JsonResource
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
            'tahun' => $this->tahun,
            'pola_spesimen_id' => $this->pola_spesimen_id,
            'pola_spesimen' => $this->polaSpesimen,
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
