<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KlasifikasiSuratRequest extends FormRequest
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
            'kode' => 'required',
            'klasifikasi' => 'required',
            'keterangan' => 'nullable',
            'user_id' => 'required',
            'is_aktif' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'kode' => 'kode',
            'klasifikasi' => 'klasifikasi',
            'keterangan' => 'keterangan',
            'user_id' => 'pengguna',
            'is_aktif' => 'status aktif',
        ];
    }
}
