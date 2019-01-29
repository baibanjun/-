<?php
namespace App\Services;

use Illuminate\Support\Facades\Redis;

/**
 * 请求认证
 *
 * @author lilin 
 * wx(tel):13408099056 
 * qq:182436607
 *
 */
class AuthService extends BaseService
{
    /**
     * api请求认证
     *
     * @param   string  $encryptedStr  须要解密的串
     * @param   string  $random        唯一不重复的值
     * @param   integer $timeStamp     时间戳
     * @return string
     */
    static public function auth($encryptedStr, $random, $timesTamp)
    {
        $startTime = microtime();
        
        //api请求最大时间差
        $apiMaxTime = config('console.apiMaxTime');
        
        $logArr = [
            'encryptedStr'  => $encryptedStr,
            'random'        => $random,
            'timesTamp'     => $timesTamp
        ];
        
        if (time() - $timesTamp > $apiMaxTime) {
            self::setLog('api请求验证,请求时间差大于配制时间', $startTime, $logArr);
            return self::returnCode('sys.authenticationFailed');
        }
        
        $pemPath    = public_path('rsa/api_rsa_private_key.pem');
        $privateKey = file_get_contents($pemPath);
        $piKey      = openssl_pkey_get_private($privateKey);
        $decrypted  = '';
        openssl_private_decrypt(base64_decode($encryptedStr), $decrypted, $piKey);
        
        $data = json_decode($decrypted, true);
        
        if (!$decrypted){
            self::setLog('api请求验证,签名解密失败', $startTime, $logArr);
            return self::returnCode('sys.authenticationFailed');
        }
        
        //从redis里找有没有用过
        $key = config('console.redis_key.api_auth').$random;
        $exists = Redis::EXISTS($key);
        if ($exists){
            self::setLog('api请求验证,使用了重复的签名', $startTime, $logArr);
            return self::returnCode('sys.authenticationFailed');
        }

        if (!isset($data['appid']) || $data['appid'] != config('console.apiAppid')){
            self::setLog('api请求验证,appid错误', $startTime, $logArr);
            return self::returnCode('sys.authenticationFailed');
        }
        
        if (!isset($data['random']) || !isset($data['timestamp']) ||  $data['random'] != $random || $data['timestamp'] != $timesTamp) {
            self::setLog('api请求验证,random和timestamp不一致', $startTime, $logArr);
            return self::returnCode('sys.authenticationFailed');
        }
        
        //验证通过加入reids,过期时间为最大时间差
        Redis::SET($key, $encryptedStr);
        Redis::EXPIRE($key, $apiMaxTime);
        
        return self::returnCode('sys.success');;
    }
}

