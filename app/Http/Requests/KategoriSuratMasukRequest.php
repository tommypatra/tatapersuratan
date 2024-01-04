<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KategoriSuratMasukRequest extends FormRequest
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
            'kategori' => 'required',
            'is_aktif' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'user_id' => 'pengguna',
            'kategori' => 'kategori',
            'is_aktif' => 'status aktif',
        ];
    }
}
