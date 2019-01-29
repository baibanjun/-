<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as BusinessUser;

class Business extends BusinessUser
{

    /**
     * 状态:正常
     * 
     * @var integer
     */
    const STATUS_NORMAL = 1;

    /**
     * 状态:冻结
     * 
     * @var integer
     */
    const STATUS_FREEZE = 2;

    protected $fillable = [
        'name',
        'tel',
        'address',
        'lng',
        'lat',
        'username',
        'mobile',
        'password',
        'salt',
        'status'
    ];
}
