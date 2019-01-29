<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 用户基本信息
 *
 * @author lilin
 *        
 */
class User extends Model
{

    /**
     * 角色:普通用户
     * 
     * @var integer
     */
    const ROLE_USER = 0;

    /**
     * 角色:达人
     * 
     * @var integer
     */
    const ROLE_TALENT = 1;

    /**
     * 状态:正常
     * 
     * @var integer
     */
    const STATUS_NORMAL = 0;

    /**
     * 冻结
     * 
     * @var integer
     */
    const STATUS_FREEZE = 1;
    
    
    /**
     * 是否关注公众号:没有
     *
     * @var integer
     */
    const IS_SUBSCRIBE_0 = 0;
    
    /**
     * 是否关注公众号:有
     *
     * @var integer
     */
    const IS_SUBSCRIBE_1 = 1;

    protected $fillable = [
        'openid',
        'nickname',
        'sex',
        'language',
        'city',
        'province',
        'country',
        'headimgurl',
        'role',
        'qr_code',
        'status',
        'salt',
        'inviter',
        'is_subscribe'
    ];

    protected $casts = [
        'qr_code' => 'json'
    ];

    /**
     * 关联订单统计
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function statistic()
    {
        return $this->hasOne('App\Models\UserOrderStatistic', 'uid');
    }

    /**
     * 关联优惠券
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lotteryUser()
    {
        return $this->hasMany('App\Models\LotteryUser', 'uid');
    }

    /**
     * 关联优惠券赠送
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lotteryUserFrom()
    {
        return $this->hasMany('App\Models\LotteryUser', 'from_uid');
    }
}
