<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class SpesimenJabatanResource extends JsonResource
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
            'kode' => ($this->kode) ? $this->kode : '',
            'jabatan' => $this->jabatan,
            'keterangan' => $this->keterangan,
            'pejabat' => $this->pejabat,
            'is_aktif' => $this->is_aktif,
            'user' => $this->user,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
        ];
        // return parent::toArray($request);    
    }
}
