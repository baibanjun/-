<?php

namespace App\Models;

class UserLoginLog extends BaseMysql
{
    protected $fillable = ['uid','token','platform','login_ip'];
    
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'uid', 'id');
    }
}
