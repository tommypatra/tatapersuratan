<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PolaSuratRequest extends FormRequest
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
            'kategori' => 'required',
            'pola' => 'required',
            'is_aktif' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'kategori' => 'kategori',
            'pola' => 'pola',
            'is_aktif' => 'status aktif',
        ];
    }
}
