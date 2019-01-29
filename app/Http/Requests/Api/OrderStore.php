<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class OrderStore extends FormRequest
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
            'product_id'    => 'required|integer',
            'standard_id'   => 'required|integer',
            'quantity'      => 'required|integer',
            'name'          => 'required|string|max:50',
            'tel'           => 'required|string|size:11',
            'area_code'     => 'nullable|max:9',
            'address'       => 'nullable|max:255',
            'remark'        => 'nullable|max:255',
            'f'             => 'required|integer',
            's'             => 'required|integer',
        ];
    }
}
