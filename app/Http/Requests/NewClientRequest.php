<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewClientRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'required',
            'cell_number' => 'required',
            'res_address' => 'required',
            'package_id' => 'required',
            'first_name' => 'required',
            'surname' => 'required',
            'contact_number' => 'required',
            'surname' => 'required',
        ];
    }
}
