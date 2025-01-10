<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $id = $this->input('id');
        // echo "ID : " . $id;
        // $id = $this->input('id');
        $rules = [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                'string',
                Rule::unique('users', 'email')->ignore($id), // Abaikan record dengan ID ini saat update
            ],
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
