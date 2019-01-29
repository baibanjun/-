<?php
namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

/**
 * 短信
 *
 * @author lilin
 *
 */
class SmsService extends BaseService
{
    /**
     * 验证手机验证码
     *
     * @param string $phone 手机号
     * @param string $code  验证码
     * @param string $type  类型
     */
    static public function verifyCode($phone, $code, $type)
    {
        switch ($type){
            case 'forget_pwd':
                $templateCode = config('sms.template.id_1');
                break;
        }
        
        //redis key
        $redisKey = config('console.redis_key.sms').$phone;
        if (!Redis::EXISTS($redisKey)){
           return self::returnCode('sys.smsDataDoesNotExist');
        }
        
        $smsCode = json_decode(Redis::GET($redisKey));
        
        if ($smsCode->code != $code){
            return self::returnCode('sys.smsCodeFail');
        }
        
        return self::returnCode('sys.success');
    }
    
    /**
     * 发送短信
     *
     * @param   string  $phone  手机号
     * @param   string  $type   类型
     * @return  boolean
     */
    static public function sendSms($phone, $type, $content=null, $ext1=null)
    {
        switch ($type){
            case 'forget_pwd':
                $templateCode = config('sms.template.id_1');
                $templateParam = [
                'code' => SmsService::getRandomNumber()
                ];
                break;
            case 'booking':
                $templateCode = config('sms.template.id_2');
                $templateParam = [
                    'content' => $content
                ];
                break;
            case 'send_code':
                $templateCode = config('sms.template.id_3');
                $templateParam = [
                    'name' => $content,
                    'code' => $ext1
                ];
                break;
        }
        
        //redis key
        $redisKey = config('console.redis_key.sms').$phone;
        
        return self::send($phone, $templateCode, $templateParam, $redisKey);
    }
    
    /**
     * 阿里云发送短信
     *
     * @param   string  $phone              手机号
     * @param   array   $templateCode       模板ID
     * @param   array   $templateParam      模板参数
     * @return  boolean
     */
    static public function send($phone, $templateCode, $templateParam, $redisKey)
    {
        $params = array();
        
        // fixme 必填：是否启用https
        $security = false;
        
        $params = [
            'PhoneNumbers' => $phone,
            'SignName'     => config('sms.signName'),
            'TemplateCode' => $templateCode,
            'TemplateParam'=> $templateParam,
        ];

        if (! empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
            $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
        }
        
        // 此处可能会抛出异常，注意catch
        $content = self::request(config('sms.accessKeyId'), config('sms.accessKeySecret'), config('sms.url'), array_merge($params, array(
            "RegionId" => "cn-hangzhou",
            "Action" => "SendSms",
            "Version" => "2017-05-25"
        )), $security);
        
        if ($content->Code === 'OK') {
            
            // 发送成功,加入到reids 有效期为15分钟
            Redis::set($redisKey, $params["TemplateParam"]);
            Redis::EXPIRE($redisKey, 15 * 60);
            
            return self::returnCode('sys.success');
        }else{
            Log::info('短信-找回密码-发送失败,手机号:'.$phone.' 阿里云返回:'.json_encode($content));
            return self::returnCode('sys.fail');
        }
        
        return $content;
    }
    
    static public function getRandomNumber()
    {
        return rand(100000, 999999);
    }
    
    /**
     * 生成签名并发起请求
     *
     * @param $accessKeyId string AccessKeyId (https://ak-console.aliyun.com/)
     * @param $accessKeySecret string AccessKeySecret
     * @param $domain string API接口所在域名
     * @param $params array API具体参数
     * @param $security boolean 使用https
     * @param $method boolean 使用GET或POST方法请求，VPC仅支持POST
     * @return bool|\stdClass 返回API接口调用结果，当发生错误时返回false
     */
    static public function request($accessKeyId, $accessKeySecret, $domain, $params, $security=false, $method='POST') {
        $apiParams = array_merge(array (
            "SignatureMethod" => "HMAC-SHA1",
            "SignatureNonce" => uniqid(mt_rand(0,0xffff), true),
            "SignatureVersion" => "1.0",
            "AccessKeyId" => $accessKeyId,
            "Timestamp" => gmdate("Y-m-d\TH:i:s\Z"),
            "Format" => "JSON",
        ), $params);
        ksort($apiParams);
        
        $sortedQueryStringTmp = "";
        foreach ($apiParams as $key => $value) {
            $sortedQueryStringTmp .= "&" . self::_encode($key) . "=" . self::_encode($value);
        }
        
        $stringToSign = "${method}&%2F&" . self::_encode(substr($sortedQueryStringTmp, 1));
        
        $sign = base64_encode(hash_hmac("sha1", $stringToSign, $accessKeySecret . "&",true));
        
        $signature = self::_encode($sign);
        
        $url = ($security ? 'https' : 'http')."://{$domain}/";
        
        try {
            $content = self::_fetchContent($url, $method, "Signature={$signature}{$sortedQueryStringTmp}");
            return json_decode($content);
        } catch( \Exception $e) {
            return false;
        }
    }
    
    static private function _encode($str)
    {
        $res = urlencode($str);
        $res = preg_replace("/\+/", "%20", $res);
        $res = preg_replace("/\*/", "%2A", $res);
        $res = preg_replace("/%7E/", "~", $res);
        return $res;
    }
    
    static private function _fetchContent($url, $method, $body) {
        $ch = curl_init();
        
        if($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        } else {
            $url .= '?'.$body;
        }
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "x-sdk-client" => "php/2.0.0"
        ));
        
        if(substr($url, 0,5) == 'https') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        
        $rtn = curl_exec($ch);
        
        if($rtn === false) {
            // 大多由设置等原因引起，一般无法保障后续逻辑正常执行，
            // 所以这里触发的是E_USER_ERROR，会终止脚本执行，无法被try...catch捕获，需要用户排查环境、网络等故障
            trigger_error("[CURL_" . curl_errno($ch) . "]: " . curl_error($ch), E_USER_ERROR);
        }
        curl_close($ch);
        
        return $rtn;
    }
}

