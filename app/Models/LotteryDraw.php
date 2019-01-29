<?php
namespace App\Models;

class LotteryDraw extends BaseMysql
{

    protected $guarded = [];

    /**
     * 九宫格抽奖
     *
     * @var integer
     */
    const LOTTERY_TYPE_1 = 1;

    /**
     * 圆盘抽奖
     *
     * @var integer
     */
    const LOTTERY_TYPE_2 = 2;

    /**
     * 已隐藏
     *
     * @var integer
     */
    const STATUS_1 = 1;

    /**
     * 正常
     *
     * @var integer
     */
    const STATUS_2 = 2;

    protected $casts = [
        'poster' => 'json'
    ];

    /**
     * 关联商家
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function business()
    {
        return $this->belongsTo('App\Models\Business', 'business_id');
    }

    /**
     * 关联奖品
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lotteryDrawList()
    {
        return $this->hasMany('App\Models\LotteryDrawList', 'lottery_draw_id');
    }
    
    public function number()
    {
        return $this->hasOne('App\Models\LotteryNumber', 'lottery_draw_id');
    }
}
