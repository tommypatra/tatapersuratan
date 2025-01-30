<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
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
    public function rules(): array
    {
        // dd($this->route());
        $id = $this->route('pola_spesiman');
        return [
            'spesimen_jabatan_id' => 'required',
            'parent_id' => 'nullable',
            'pola_surat_id' => [
                'required',
                Rule::unique('pola_spesimens')
                    ->where('spesimen_jabatan_id', $this->spesimen_jabatan_id)
                    ->ignore($id),
            ],

        ];
    }

    public function attributes()
    {
        return [
            'pola_surat_id' => 'pola surat',
            'parent_id' => 'rujukan indeks',
            'spesimen_jabatan_id' => 'spesimen jabatan',
        ];
    }
}
