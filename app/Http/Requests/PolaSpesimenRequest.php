<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PolaSpesimenRequest extends FormRequest
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
            'pola_surat_id' => 'required',
            'spesimen_jabatan_id' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'pola_surat_id' => 'pola surat',
            'spesimen_jabatan_id' => 'spesiman jabatan',
        ];
    }
}
