<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddMenuRequest extends FormRequest
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
            'ingredients' => 'required',
            'category_id' => 'required',
            'menu_type' => 'required',
            'price' => 'required',
            'image' => 'max:1',
            'video' => 'max:20480',
        ];
    }
}
