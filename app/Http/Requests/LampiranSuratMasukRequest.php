<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LampiranSuratMasukRequest extends FormRequest
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
            'upload_id' => 'required',
            'surat_masuk_id' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'upload_id' => 'file upload',
            'surat_masuk_id' => 'surat masuk',
        ];
    }
}
