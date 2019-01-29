<?php
namespace App\Http\Requests\Api\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Product;

class ProductCreateOrUpdate extends FormRequest
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
            'type' => [
                'required',
                Rule::in([
                    Product::TYPE_LOCAL,
                    Product::TYPE_CIRCUM,
                    Product::TYPE_PLACE
                ])
            ],
            'business_id' => 'required',
            'city_code' => 'required',
            'send_sms_or_not' => [
                'required',
                Rule::in([
                    Product::IS_NO,
                    Product::IS_YES
                ])
            ],
//             'booking_information' => 'required_if:send_sms_or_not,1',
            'poster.*.name' => 'required',
            'poster.*.width' => 'required|integer|size:750',
            'poster.*.height' => 'required|integer|size:1334',
            'pics' => 'required',
            'content' => 'required',
            'subtitle' => 'required',
            'distribution' => 'required|size:3',
            'distribution.*.class_type' => 'required',
            'distribution.*.type' => 'required',
            'distribution.*.value' => 'required',
            'standard' => 'required',
            'standard.*.name' => 'required',
            'standard.*.sale_price' => 'required',
            'standard.*.price' => 'required',
            'standard.*.quantity_sold' => 'required|max:10000000',
            'standard.*.onhand' => 'required|max:10000000',
            'is_countdown' => [
                'required',
                Rule::in([
                    Product::IS_NO,
                    Product::IS_YES
                ])
            ],
            'time_limit' => 'required_if:is_countdown,1'
        ];
    }
}
