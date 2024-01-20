<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TtdQrcodeRequest extends FormRequest
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
            'tanggal' => 'required|date_format:Y-m-d',
            'no_surat' => 'required',
            'kode' => 'nullable',
            'perihal' => 'required',
            'pejabat' => 'required',
            'jabatan' => 'required',
            'file' => 'required|file|mimes:pdf|max:15000',
            'is_diterima' => 'nullable',
            'is_diajukan' => 'nullable',
            'catatan' => 'nullable',
            'user_ttd_id' => 'required',
            'qrcode' => 'nullable',
            'user_id' => 'required',
        ];

        if ($this->isMethod('put')) {
            $rules['pejabat'] = 'nullable';
            $rules['jabatan'] = 'nullable';
            $rules['file'] = 'nullable|file|mimes:pdf|max:15000';
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'tanggal' => 'tanggal surat',
            'no_surat' => 'nomor surat',
            'kode' => 'kode',
            'perihal' => 'perihal',
            'pejabat' => 'pejabat',
            'jabatan' => 'jabatan',
            'file' => 'lampiran surat',
            'is_diterima' => 'status diterima',
            'is_diajukan' => 'status diajukan',
            'catatan' => 'catatan',
            'user_ttd_id' => 'pejabat tanda tangan',
            'user_id' => 'sumber',
        ];
    }
}
