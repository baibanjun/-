<?php
namespace App\Services;

use GuzzleHttp\Client as httpClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

/**
 * 基类
 * @author lilin
 *
 */
class BaseService
{
    const CODE_NAME = 'console.code.';
    
    const SUCCESS_CODE = '0000';
    
    public $uid;

    public function __construct(){
        $this->uid = Auth::id();
    }

    /**
     * 对返回数据统一格式
     *
     * @param   string  $code
     * @param   array   $data
     * @return  array
     */
    public static function returnCode ($code, $data=[])
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
    public static function sendRequest($url, $parameters = [], $type = 'GET')
    {
        $startTime = microtime();
        
        $client = new httpClient();
        $res = $client->request($type, $url, $parameters);
        
        $result = json_decode($res->getBody(), true);
        self::setLog('http curl请求', $startTime, $result);
        return $result;
    }
    
    /**
     * 统一格式输出日志
     *
     * @param string    $typeName   类别名
     * @param array     $log        日志详情
     * @param integer   $startTime  开始时间
     */
    public static function setLog($typeName, $startTime = 0, $log = [])
    {
        $diffTime = $startTime ? self::getDiffMicrotime($startTime) : '0.00';
        
        Log::info($typeName . ' 参数:' . json_encode($log) . '执行用时:' . $diffTime);
    }
    
    /**
     * 计算时间差
     * @param integer $sTime
     */
    public static function getDiffMicrotime($sTime)
    {
        $sTime = explode(' ', $sTime);
        $mTime = explode(' ', microtime());
        return roundDown((($mTime[1] + $mTime[0]) - ($sTime[1] + $sTime[0])), 3);
    }
}