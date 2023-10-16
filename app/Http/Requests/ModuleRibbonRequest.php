<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModuleRibbonRequest extends FormRequest
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
            'ribbon_name' => 'required',
            'ribbon_path' => 'required',
            'description' => 'required',
            'access_level' => 'required',
            'sort_order' => 'bail|required|integer|min:0'
        ];
    }
}
