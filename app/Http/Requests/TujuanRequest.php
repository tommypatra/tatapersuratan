<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TujuanRequest extends FormRequest
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
            ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // $id = $this->input('id');
        return [
            'user_id' => 'required',
            // 'created_by' => $id ? 'nullable' : 'required',
            'surat_masuk_id' => 'required',
            'waktu_akses' => 'nullable|date_format:Y-m-d H:i:s',
        ];
    }

    public function attributes()
    {
        return [
            'user_id' => 'pengguna',
            'surat_masuk_id' => 'surat masuk',
            'waktu_akses' => 'waktu akses',
        ];
    }
}
