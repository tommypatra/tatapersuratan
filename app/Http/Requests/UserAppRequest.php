<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserAppRequest extends FormRequest
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
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
        ];

        if ($this->isMethod('put')) {
            $rules['password'] = 'nullable|min:8';
        } else {
            $rules['password'] = 'required|min:8';
        }
        return $rules;
    }


    public function attributes()
    {
        return [
            'name' => 'nama',
            'email' => 'email',
            'password' => 'password',
        ];
    }
}
