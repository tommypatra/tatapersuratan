<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpesimenJabatanRequest extends FormRequest
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
            'kode' => 'nullable',
            'jabatan' => 'required',
            'keterangan' => 'nullable',
            'user_pejabat_id' => 'required',
            'is_aktif' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'kode' => 'kode',
            'jabatan' => 'jabatan',
            'keterangan' => 'keterangan',
            'user_pejabat_id' => 'pejabat',
            'is_aktif' => 'status aktif',
        ];
    }
}
