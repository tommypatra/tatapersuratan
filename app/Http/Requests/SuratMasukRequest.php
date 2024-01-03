<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SuratMasukRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required',
            'kategori_surat_masuk_id' => 'required',
            'no_agenda' => 'required',
            'no_surat' => 'required',
            'perihal' => 'required',
            'asal' => 'required',
            'tanggal' => 'required|date_format:Y-m-d',
            'tempat' => 'required',
            'ringkasan' => 'nullable|string',
        ];
    }

    public function attributes()
    {
        return [
            'user_id' => 'pengguna',
            'kategori_surat_masuk_id' => 'kategori',
            'no_agenda' => 'no agenda',
            'no_surat' => 'no surat',
            'perihal' => 'perihal',
            'asal' => 'asal',
            'tanggal' => 'tanggal',
            'tempat' => 'tempat',
            'ringkasan' => 'ringkasan',
        ];
    }
}
