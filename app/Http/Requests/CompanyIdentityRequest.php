<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyIdentityRequest extends FormRequest
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
            'mailing_address' => 'email',
            'support_email' => 'email',
           // 'company_logo' => 'image|mimes:jpeg,png,jpg|max:3000',
            //'login_background_image' => 'image|mimes:jpeg,png,jpg|max:3000',
        ];
    }
}
