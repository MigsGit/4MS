<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentDetailRequest extends FormRequest
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
            "ecrs_id" => 'required',
            "document_number" => 'required',
            "person_in_charge" => 'required',
            "revision_no" => 'required',
            "revision_due_date" => 'required',
        ];
    }
}
