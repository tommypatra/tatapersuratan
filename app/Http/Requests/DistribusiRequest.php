<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DistribusiRequest extends FormRequest
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
        $id = $this->input('id');
        if ($id)
            $this->merge([
                'waktu_akses' => date('Y-m-d H:i:s'),
                'is_aktif' => 1,
            ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'surat_keluar_id' => 'required',
            'waktu_akses' => 'nullable',
            'user_id' => 'required',
            // 'is_aktif' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'surat_keluar_id' => 'surat keluar',
            'waktu_akses' => 'waktu akses',
            'user_id' => 'pengguna',
        ];
    }
}
