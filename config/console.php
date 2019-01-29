<?php
return [
    'appId'         => env('APPID'),
    'appSecret'     => env('APPSECRET'),
    'auth_index'    => env('AUTH_INDEX'),
    
    'wx_mchId'      => env('WX_MCHID'),
    'wx_notify_url' => env('NOTIFY_URL'),
    'wx_mch_secret' => env('WX_MCH_SECRET'),
    'wx_pay_url'    => env('WX_PAY_URL'),
    'wx_pay_expiration_time' => 30 * 60, //微信支付过期时间
    'wx_auth_account' => env('WX_AUTH_ACCOUNT'), //开发者微信号
    'wx_subscribe_tip'=>"Hey，么么哒！\n\n欢迎关注吃喝玩乐成都联盟！\n特价爆款产品时时上新哦~[奸笑]\n\n客服热线：40088-59998\n客服微信：18215503427",//关注公众号后提示
    'msg_template_id' => env('MSG_TEMPLATE_ID'),
    'msg_template_first'=> env('MSG_TEMPLATE_FIRST'),

    'poster_url' => env('POSTER_URL'),//海报前端url

    'wx_decrypt' => [
        'token'             => env('WX_DECRYPT_TOKEN'),
        'encoding_aes_key'  => env('WX_DECRYPT_AES_KEY'),
    ],
    
    'redis_key' => [
        'access_token'          => 'wx:access_token',
        'jsapi_ticket'          => 'wx:jsapi',
        
        'auth_access_token'     => 'wx:auth:access_token:openid_',
        'auth_refresh_token'    => 'wx:auth:refresh_token:openid_',
        'wx_pay_sign'           => 'wx:pay:sign:order_id_',
        'wx_pay_onhand'         => 'wx:pay:onhand:standardid_',
        
        'api_auth'              => 'api_auth:',
        
        'sms'                   => 'sms:forgetPwd:',
        
        'upload_img'            => 'product:image:',
    ],
    
    //二维码场景值
    'qr_code' => [
        'user' => 'user', //用户的二维码
    ],
    
    'web_index'                 => env('WEB_INDEX'),
    'pic_url'                   => env('PIC_URL'),
    'qr_url'                    => env('QR_URL'),//微信二维码URL
    'business_verify_order_url' => env('BUSINESS_VERIFY_ORDER_URL'), // 商家平台验证订单
    'business_verify_coupon_url' => env('BUSINESS_VERIFY_COUPON_URL'), // 商家平台验证优惠卷
    'join_team_url'             => env('JOIN_TEAM_URL'), // 加入团队
    'create_talent_url'         => env('CREATE_TALENT_URL'), // 申请成为达人
    'reg_pwd_sn'                => 'LiLIn211,Vx:13408099056', // 注册常量
    'checkAuth'                 => env('CHECK_AUTH', true),
    'login_delete_other_log'    => env('LOGIN_DELETE_OTHER_LOG', true),
    'apiMaxTime'                => 15 * 60, // api请求最大时间差
    'apiAppid'                  => 'LiLIn211,Vx:13408099056', // api请求验证时使用
    'withdraw_min_money'        => 1.00, // 最少提现多少
    'sys_deliver_goods_day'     => 7,//系统自动收货时间(天)

    //静态配制
    'static_url'    => env('STATIC_URL'),
    'static_v'      => env('STATIC_V'),
    'api_url'       => env('API_URL'),
    'pic_url'       => env('PIC_URL'),
    'app_ewm'       => env('APP_EWM'),
    
    'code' => [
        'sys' => [
            'success' => '0000', // 成功
            'fail' => '0001', // 失败
            'dataDoesNotExist' => '0002', // 数据不存在
            'dataDoesExist' => '0003', // 数据已存在
            'statusIsNotNormal' => '0004', // 状态不正常
            'insufficientFunds' => '0005', // 金额不够
            'amountIsTooSmall' => '0006', // 金额太小
            'unprocessedWithdrawal' => '0007', // 有正在处理的提现
            'rolesAlreadyExist' => '0008', // 角色已存在
            'incorrect_password' => '0009', // 密码不正确
            'codeIsAuthenticated' => '0010', // 已核销过了
            'wx_code_error' => '0011', // 微信认证code错误
            'wx_access_token_error' => '0012', // 微信认证access_token错误
            'wx_refresh_token_error' => '0013', // 微信认证使用refresh_token请求时错误
            'wxNeedsToBeReauthorized' => '0014', // 微信须要重新授权
            'wxPayFail' => '0015', // 微信支付失败
            'userStatusIsNotNormal' => '0016', // 用户状态不正常
            'authenticationFailed' => '0017', // API认证失败,检查token
            'authenticationFailedTime' => '0018', // API认证失败,检查时间差
            'authenticationFailedExists' => '0019', // API认证失败,检查签名在配制的最大时间内使用过
            'authenticationFailedEncrypted' => '0020', // API认证失败,签名解决失败
            'smsDataDoesNotExist' => '0021', // 验证码数据不存在
            'smsCodeFail' => '0022', // 验证码错误
            'dontAttention' => '0023', // 未关注公众号
            'busStatusIsNotNormal' => '0024', // 商家状态不正常
            'proStatusIsNotNormal' => '0025', // 产品状态不正常
            'insufficientInventory' => '0026', // 库存不足
            'talentStatusIsNotNormal' => '0027', // 达人状态不正常
            'teamAlreadyExist' => '0028', // 已在其它团队
            'productEndOfCountdown' => '0029', // 产品倒计时结束
            'captainsAndPlayerNotAlike' => '0030', // 队长和队员不能相同
            'orderHasExpired' => '0031', // 订单已过期

            'lotteryDoesExist' => '0032', // 活动不存在或者已结束
            'prizeDoesExist' => '0033', // 奖品没有了
            'notEnoughLotteries' => '0034', // 抽奖次数不够
            'couponsDoesExist' => '0035', // 优惠卷不存在
            'businessApplyExist' => '0036', // 商家申请已存在,不能再申请
            'couponHasExpired' => '0037', // 优惠卷已过期
            'prizeIsGone' => '0038', // 您分享得太晚，奖品已被他人领走了
        ]
    ]
];