<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GrupUserRequest extends FormRequest
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
            'grup_id' => 'required',
            'user_id' => 'required',
            'is_aktif' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'user_id' => 'pengguna',
            'grup_id' => 'grup',
            'is_aktif' => 'status aktif',
        ];
    }
}
