<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfilRequest extends FormRequest
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
            'jenis_kelamin' => 'required',
            'user_id' => 'required',
            'alamat' => 'required',
            'hp' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        ];
    }

    public function attributes()
    {
        return [
            'jenis_kelamin' => 'jenis kelamin',
            'user_id' => 'user id',
            'foto' => 'foto',
            'alamat' => 'alamat',
            'hp' => 'hp',
        ];
    }
}
