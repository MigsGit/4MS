<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManFileRequest extends FormRequest
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
            'man_ref.*' =>  'required | file | mimes:xlsm,xlsx, xls, csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel', //man_ref
        ];
    }
}
