<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SuratKeluarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    public function prepareForValidation()
    {
        // $id = $this->input('id');
        // $tanggal = $this->input('tanggal');
        // $no_indeks = $this->input('no_indeks');
        // $no_sub_indeks = $this->input('no_sub_indeks');
        // $akses_pola_id = $this->input('akses_pola_id');
        // $klasifikasi_surat_id = $this->input('klasifikasi_surat_id');

        // $generateValue = generateNomorKeluar($tanggal, $akses_pola_id, $klasifikasi_surat_id, $no_indeks, $no_sub_indeks, $id);
        // $this->merge([
        //     'no_surat' => $generateValue['no_surat'],
        //     'no_indeks' => $generateValue['no_indeks'],
        //     'no_sub_indeks' => $generateValue['no_sub_indeks'],
        //     'pola' => $generateValue['pola'],
        // ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function rules()
    {
        return [
            'user_id' => 'required',
            'klasifikasi_surat_id' => 'nullable|integer',
            'no_sub_indeks' => 'nullable|integer',
            'pola_spesimen_id' => 'nullable|integer',
            'no_surat' => 'nullable',
            'no_indeks' => 'nullable',
            'is_diajukan' => 'nullable',
            'is_diterima' => 'nullable',
            'verifikator' => 'nullable',
            'catatan' => 'nullable',
            'perihal' => 'required',
            'asal' => 'required',
            'tanggal' => 'required|date_format:Y-m-d',
            'tujuan' => 'nullable|string',
            'pola' => 'nullable|string',
            'ringkasan' => 'nullable|string',
        ];
    }

    public function attributes()
    {
        return [
            'user_id' => 'pengguna',
            'klasifikasi_surat_id' => 'klasifikasi',
            'no_sub_indeks' => 'no sub indeks',
            'pola_spesimen_id' => 'pola spesimen',
            'no_surat' => 'no surat',
            'no_indeks' => 'no indeks',
            'perihal' => 'perihal',
            'asal' => 'asal',
            'tanggal' => 'tanggal',
            'tujuan' => 'tujuan',
            'pola' => 'pola',
            'ringkasan' => 'ringkasan',
        ];
    }
}
