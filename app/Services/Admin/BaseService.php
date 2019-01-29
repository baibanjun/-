<?php
namespace App\Services\Admin;

use GuzzleHttp\Client as httpClient;
/**
 * 基类
 * @author lilin
 *
 */
class BaseService
{
    const CODE_NAME = 'admin.code.';
    
    const SUCCESS_CODE = '0000';
    
    /**
     * 对返回数据统一格式
     *
     * @param   string  $code
     * @param   array   $data
     * @return  array
     */
    static public function returnCode ($code, $data=[])
    {
        $code = self::CODE_NAME.$code;
    
        return ['code'=>config($code), 'data'=>$data];
    }
    
    /**
     * 模拟URL请求
     *
     * @param   string      $url            请求的URL
     * @param   array       $parameters     请求参数,如果要求json格式 ['json' => ['foo' => 'bar']]
     * @param   string      $type           请求类型 get post put ...
     * @return \stdClass
     */
    static protected function sendRequest($url, $parameters = [], $type = 'GET')
    {
        $client = new httpClient();
        $res = $client->request($type, $url, $parameters);
        return json_decode($res->getBody(), true);
    }
}