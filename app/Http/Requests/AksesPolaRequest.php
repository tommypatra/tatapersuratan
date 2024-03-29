<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AksesPolaRequest extends FormRequest
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
            'tahun' => 'required',
            'pola_spesimen_id' => 'required',
            'user_id' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'tahun' => 'tahun',
            'pola_spesimen_id' => 'pola spesimen',
            'user_id' => 'pengguna',
        ];
    }
}
