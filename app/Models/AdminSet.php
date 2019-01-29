<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminSet extends Model
{

    /**
     * 邀请关注公众号奖励
     * @var string
     */
    const TYPE_NAME_ATTENTION = 'attention';
    
    /**
     * 提现提示
     * @var string
     */
    const TYPE_NAME_WITHDRAWAL_PROMPT = 'withdrawal_prompt';
    
    /**
     * 组建团队设置
     * @var string
     */
    const TYPE_NAME_TEAM_SETTING = 'team_setting';
    
    /**
     * 福利群设置
     * @var string
     */
    const TYPE_NAME_WEICHAT_GROUP = 'weichat_group';

    /**
     * 用户抽奖次数设置
     * @var string
     */
    const TYPE_NAME_LOTTERY_DRAW = 'lottery_draw';
    
    /**
     * 商家入驻申请结果提醒设置
     * @var string
     */
    const TYPE_NAME_BUSINESS_ENTER_ATTENTION = 'business_enter_attention';
    
    protected $guarded = [];

    protected $casts = [
        'value' => 'json'
    ];
}
