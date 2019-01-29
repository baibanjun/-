<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LotteryDrawList extends Model
{
    protected $guarded = [];
    
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
     * 否
     *
     * @var integer
     */
    const IS_NO = 0;
    
    /**
     * 是
     *
     * @var integer
     */
    const IS_YES = 1;
    
    protected $casts = ['pic'=>'json'];
}
