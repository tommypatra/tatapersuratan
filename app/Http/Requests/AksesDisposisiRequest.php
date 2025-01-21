<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AksesDisposisiRequest extends FormRequest
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
            'tahun' => 'required',
            'spesimen_jabatan_id' => 'required',
            'user_id' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'tahun' => 'tahun',
            'spesimen_jabatan_id' => 'jabatan',
            'user_id' => 'pengguna',
        ];
    }
}
