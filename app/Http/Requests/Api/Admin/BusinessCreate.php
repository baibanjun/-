<?php

namespace App\Http\Requests\Api\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BusinessCreate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return TRUE;
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
            'tel' => 'required',
            'address' => 'required',
            'lng' => 'required',
            'lat' => 'required',
            'username' => 'required',
            'mobile' => 'required',
            'password' => 'required',
            'salt' => 'required',
        ];
    }
}
