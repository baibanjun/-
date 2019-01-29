<?php

namespace App\Models;


class BusinessLoginLog extends BaseMysql
{
    protected $fillable = ['uid','token','platform','login_ip'];
}
