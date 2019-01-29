<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAccountRecord extends Model
{

    /**
     * 一级分销收入
     * 
     * @var integer
     */
    const OBJECT_TYPE_1 = 1;

    /**
     * 团队收入
     * 
     * @var integer
     */
    const OBJECT_TYPE_2 = 2;

    /**
     * 推荐用户收入
     * 
     * @var integer
     */
    const OBJECT_TYPE_3 = 3;

    /**
     * 提现扣款
     * 
     * @var integer
     */
    const OBJECT_TYPE_4 = 4;

    /**
     * 提现驳回
     * 
     * @var integer
     */
    const OBJECT_TYPE_5 = 5;
    
    /**
     * 二级分销收入
     *
     * @var integer
     */
    const OBJECT_TYPE_6 = 6;

    protected $fillable = [
        'uid',
        'money',
        'now_money',
        'object_type',
        'object_id',
        'primary_distribution',
        'secondary_distribution',
        'recommended_user',
        'team',
        'key'
    ];

    /**
     * 关联用户
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'uid');
    }
    
    /**
     * 关联达人
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userTalent()
    {
        return $this->belongsTo('App\Models\UserTalent', 'uid');
    }
    
    /**
     * 被邀请人关联用户
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function objectUser()
    {
        return $this->belongsTo('App\Models\User', 'object_id');
    }
    
    /**
     * 关联订单
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order(){
        return $this->belongsTo('App\Models\Order','object_id','id');
    }
}
