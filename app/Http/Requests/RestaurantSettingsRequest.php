<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RestaurantSettingsRequest extends FormRequest
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
            'colour_one' => 'required',
            'colour_two' => 'required',
            'colour_three' => 'required',
            'mins_one' => 'required',
            'mins_two' => 'required',
            'mins_three' => 'required',
        ];
    }
}
