<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LotteryNumber extends Model
{
    /**
     * 是否分享 没有
     * @var integer
     */
    const IS_SHARE_0 = 0;
    
    /**
     * 是否分享 有
     * @var integer
     */
    const IS_SHARE_1 = 1;
    
    
    protected $guarded = [];
}
