<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTeam extends Model
{
    
    /**
     * 正常(通过)
     *
     * @var integer
     */
    const STATUS_1 = 0;
    
    /**
     * 冻结
     *
     * @var integer
     */
    const STATUS_2 = 1;
    
    /**
     * 主键
     *
     * @var string
     */
    protected $primaryKey = 'uid';
    
    protected $guarded = [];
    
    /**
     * 关联用户
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'uid');
    }
    
    /**
     * 关联达人
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userTalent()
    {
        return $this->belongsTo('App\Models\UserTalent', 'uid');
    }
    
    /**
     * 关联账户
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo('App\Models\UserAccount', 'uid');
    }
}
