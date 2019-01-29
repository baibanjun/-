<?php

namespace App\Models;

class NewsInfo extends BaseMysql
{
    /**
     * 类别
     * @var integer
     */
    const TYPE_PSP = 1;
    
    /**
     * 2台
     * @var integer
     */
    const TYPE_TV = 2;
    
    /**
     * 探店
     * @var integer
     */
    const TYPE_DISCOVERY = 3;
    
    protected $casts = ['pics'=>'json'];
}
