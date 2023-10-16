<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddDependencyRequest extends FormRequest
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
            'dependency_first_name' => 'required',
            'dependency_surname' => 'required',
            'dependency_code' => 'required',
        ];
    }
}
