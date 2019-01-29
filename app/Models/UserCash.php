<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCash extends Model
{
    protected $guarded = [];
    
    /**
     * 状态:已扣款
     * @var integer
     */
    const STATUS_1 = 1;
    
    /**
     * 状态:已打款
     * @var integer
     */
    const STATUS_2 = 2;
    
    /**
     * 状态:已驳回
     * @var integer
     */
    const STATUS_3 = 3;
    
    /**
     * 关联用户
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'uid');
    }
    
    /**
     * 关联达人用户
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userTalent()
    {
        return $this->belongsTo('App\Models\UserTalent', 'uid');
    }
}
