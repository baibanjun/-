<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\LotteryDrawList;
use App\Models\LotteryDraw;

class LotteryDrawStore extends FormRequest
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
            'title' => 'required',
            'lottery_type' => [
                'required',
                Rule::in([
                    LotteryDraw::LOTTERY_TYPE_1,
                    LotteryDraw::LOTTERY_TYPE_2
                ])
            ],
            'business_id' => 'required|integer',
            'poster' => 'required',
            
            'draw_data.*.name' => 'required',
            'draw_data.*.draw_type' => [
                'required',
                Rule::in([
                    LotteryDrawList::DRAW_TYPE_1,
                    LotteryDrawList::DRAW_TYPE_2
                ])
            ],
            'draw_data.*.inventory' => 'required',
            'draw_data.*.probability' => 'required',
            'draw_data.*.start_date' => 'required_if:draw_data.*.draw_type,'.LotteryDrawList::DRAW_TYPE_1,
            'draw_data.*.end_date' => 'required_if:draw_data.*.draw_type,'.LotteryDrawList::DRAW_TYPE_1,
            'draw_data.*.use_condition' => 'required_if:draw_data.*.draw_type,'.LotteryDrawList::DRAW_TYPE_1,
            'draw_data.*.pic' => 'required_if:lottery_type,'.LotteryDraw::LOTTERY_TYPE_1,
            'draw_data.*.description' => 'required_if:draw_data.*.draw_type,'.LotteryDrawList::DRAW_TYPE_1,
            'draw_data.*.is_auto_hidden' => [
                'required',
                Rule::in([
                    LotteryDrawList::IS_NO,
                    LotteryDrawList::IS_YES
                ])
            ],
        ];
    }
}
