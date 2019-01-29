<?php

namespace App\Http\Requests\Api\My;

use Illuminate\Foundation\Http\FormRequest;

class TalentStore extends FormRequest
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
            'name' => 'required|max:50',
            'mobile' => 'required|max:11',
        ];
    }
}
