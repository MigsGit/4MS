<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MethodFileRequest extends FormRequest
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
            'methodRefBefore.*' => 'file|mimes:jpeg,png',
            'methodRefAfter.*' => 'file|mimes:jpeg,png',
            'ecrsId' => 'required',
            'methodsId' => 'required',
        ];
    }
}
