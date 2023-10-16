<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class AddUserRequest extends FormRequest
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
            'first_name' => 'required',
            'surname' => 'required',
            'email' => 'required|email|unique:users',
            'cell_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:hr_people',
        ];
    }
}
