<?php
return [
    'accessKeyId'       => env('ALISMS_KEY'),
    'accessKeySecret'   => env('ALISMS_SECRETKEY'),
    'signName'          => env('ALISMS_SIGNNAME'),      //签名
    'url'               => env('ALISMS_URL'),           //请求的URL
    
    // 短信模板
    'template' => [
        'id_1' => 'SMS_150756218', //商家找回密码
        'id_2' => 'SMS_151905159', //预约
        'id_3' => 'SMS_155355892', //支付成功后发带有核销码的短信
    ]
];