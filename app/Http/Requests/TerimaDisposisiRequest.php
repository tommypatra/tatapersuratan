<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TerimaDisposisiRequest extends FormRequest
{
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
            'surat_masuk_id' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'surat_masuk_id' => 'surat masuk',
        ];
    }
}
