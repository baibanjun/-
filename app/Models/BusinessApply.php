<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessApply extends Model
{

    protected $guarded = [];

    /**
     * 申请
     * 
     * @var integer
     */
    const STATUS_0 = 0;

    /**
     * 通过
     * 
     * @var integer
     */
    const STATUS_1 = 1;

    /**
     * 驳回
     * 
     * @var integer
     */
    const STATUS_2 = 2;
}
