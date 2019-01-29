<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\WeixinService;

class BaseController extends Controller
{
    const SUCCESS_CODE = '0000';
    
    /**
     * 微信 access_token
     * @var string
     */
    protected  $access_token;
    
    public function __construct (Request $request)
    {   
        //微信验证
        $this->access_token = WeixinService::getAccessToken();
    }
}
