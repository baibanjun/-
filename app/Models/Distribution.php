<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 分销分配规则
 *
 * @author lilin
 *
 */
class Distribution extends Model
{
    protected $guarded = [];
    
    /**
     * 一级分销类别
     * @var integer
     */
    const CLASS_TYPE_PRIMARY_DISTRIBUTION = 1;
    
    /**
     * 二级分销类别
     * @var integer
     */
    const CLASS_TYPE_SECONDARY_DISTRIBUTION = 2;
    
    /**
     * 团队分销类别
     * @var integer
     */
    const CLASS_TYPE_TEAM_DISTRIBUTION = 3;
    
    /**
     * 按百分比分配
     * @var integer
     */
    const ALLOCATION_TYPE_PERCENT = 1;
    
    /**
     * 按固定金额分配
     * @var integer
     */
    const ALLOCATION_TYPE_MONEY = 2;
}
