<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EcrApprovalRequest extends FormRequest
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
            'requested_by' => 'required',
            'technical_evaluation' => 'required',
            'reviewed_by' => 'required',
            'qad_approved_by_external' => 'required',
            'qad_approved_by_internal' => 'required',
            'qad_checked_by' => 'required',
        ];
    }
}
