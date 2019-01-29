<?php
/**
 * Created by PhpStorm.
 * User: lewis
 * Date: 2017/3/3
 * Time: 12:17
 */

// 设置COS所在的区域，对应关系如下：
//     华南  -> gz
//     华东  -> sh
//     华北  -> tj
$location = 'cd';
// 版本号
$version = 'v4.2.3';

return [
    'version' => $version,
    'api_cos_api_end_point' =>  'http://cd.file.myqcloud.com/files/v2/',
    'app_id' => env('TX_APPID'),
    'secret_id' => env('TX_SECRETID'),
    'secret_key' => env('TX_SECRETKEY'),
    'user_agent' => 'cos-php-sdk-'.$version,
    'time_out' => env('UPLOAD_SIGN_TIME'),
    'location' => $location,
];