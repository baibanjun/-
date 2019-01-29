<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LotteryUser extends Model
{

    /**
     * 未使用
     *
     * @var integer
     */
    const STATUS_0 = 0;

    /**
     * 已使用
     *
     * @var integer
     */
    const STATUS_1 = 1;

    /**
     * 优惠券
     *
     * @var integer
     */
    const DRAW_TYPE_1 = 1;

    /**
     * 谢谢参与
     *
     * @var integer
     */
    const DRAW_TYPE_2 = 2;
    
    /**
     * 未分享
     *
     * @var integer
     */
    const IS_SHARE_0 = 0;
    
    /**
     * 已分享
     *
     * @var integer
     */
    const IS_SHARE_1 = 1;
    
    /**
     * 未转赠
     *
     * @var integer
     */
    const IS_GIVE_0 = 0;
    
    /**
     * 已转赠
     *
     * @var integer
     */
    const IS_GIVE_1 = 1;

    protected $guarded = [];

    /**
     * 关联用户
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'uid', 'id');
    }

    /**
     * 管理赠送用户
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userFrom()
    {
        return $this->belongsTo('App\Models\User', 'from_uid', 'id');
    }

    /**
     * 关联奖品
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function prize()
    {
        return $this->belongsTo('App\Models\LotteryDrawList', 'prize_id', 'id');
    }

    /**
     * 关联抽奖活动
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lotteryDraw()
    {
        return $this->belongsTo('App\Models\LotteryDraw', 'lottery_draws_id')->withTrashed();
    }
}
