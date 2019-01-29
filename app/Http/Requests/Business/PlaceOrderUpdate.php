<?php

namespace App\Http\Requests\Business;

use Illuminate\Foundation\Http\FormRequest;

class PlaceOrderUpdate extends FormRequest
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
            'express_company'  => 'required|max:255',
            'express_number'  => 'required|max:255',
        ];
    }
}
