<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'name' => 'required|string|max:255',
			'email' => 'required|email|max:255|unique:companies',
			//'contact_email' => 'required|email|max:255|unique:contacts_person',
			'cell_number' => 'required',
			'res_address' => 'required|string|max:500',
			'package_id' => 'required|exists:packages,id',
			'first_name' => 'required|string|max:255',
			'surname' => 'required|string|max:255',
			'contact_number' => 'required',
        ];
    }
}
