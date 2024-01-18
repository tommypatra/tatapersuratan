<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class SuratKeluarResource extends JsonResource
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
            'no_indeks' => formatNotNull($this->no_indeks),
            'no_sub_indeks' => formatNotNull($this->no_sub_indeks),
            'no_surat' => formatNotNull($this->no_surat),
            'perihal' => $this->perihal,
            'asal' => $this->asal,

            'is_diterima' => $this->is_diterima,
            'is_diajukan' => $this->is_diajukan,
            'catatan' => $this->catatan,
            'verifikator' => $this->verifikator,

            'tujuan' => $this->tujuan,
            'pola' => $this->pola,
            'ringkasan' => $this->ringkasan,
            'klasifikasi_surat_id' => $this->klasifikasi_surat_id,
            'pola_surat' => $this->aksesPola->polaSurat,
            'user_id' => $this->user_id,
            'akses_pola_id' => $this->akses_pola_id,

            'user' => new UserAppResource($this->user),
            'distribusi' => DistribusiResource::collection($this->distribusi),
            'jumlah_distribusi' => count($this->distribusi),
            'spesimen_jabatan' => $this->aksesPola->spesimenJabatan,
            'klasifikasi_surat' => $this->klasifikasiSurat,
            'lampiran_surat_keluar' => $this->lampiranSuratKeluar,

            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'jumlah_lampiran' => LampiranSuratKeluarResource::collection($this->lampiranSuratKeluar)->count(),
        ];
        // return parent::toArray($request);
    }
}
