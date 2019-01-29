<?php
namespace App\Http\Requests\Api\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Business;

class BusinessUpdate extends FormRequest
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
        if (request('update_type') == 'status') {
            return [
                'status' => [
                    'required',
                    Rule::in([
                        Business::STATUS_NORMAL,
                        Business::STATUS_FREEZE
                    ])
                ]
            ];
        } else {
            return [
                'tel' => 'required',
                'address' => 'required',
                'lng' => 'required',
                'lat' => 'required',
                'username' => 'required',
                'mobile' => 'required'
            ];
        }
    }
}
