<?php

namespace App\Http\Requests\Do;

use Illuminate\Foundation\Http\FormRequest;

class AddRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'reference_num'=>'required|unique:d_os,reference_num',
        ];
    }
    public function messages(){
        return [
            'required' => "The filed is required",
            'unique' => "This :attribute is already used. Please try another",
        ];
    }
}
