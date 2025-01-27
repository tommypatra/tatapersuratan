<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LampiranSuratKeluarRequest extends FormRequest
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
            'file' => 'required|file|mimes:heif,jpeg,jpg,png,pdf,doc,docx,ppt,pptx,xls,xlsx|max:8000', // Maksimal 2MB
            'surat_keluar_id' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'file' => 'file upload',
            'surat_keluar_id' => 'surat keluar',
        ];
    }
}
