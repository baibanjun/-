<?php
namespace App\Services;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

/**
 * 微信网页授权
 * 
 * 第一步：用户同意授权，获取code
 * 第二步：通过code换取网页授权access_token
 * 第三步：刷新access_token（如果需要）
 * 第四步：拉取用户信息(需scope为 snsapi_userinfo)
 * 检验授权凭证（access_token）是否有效
 *
 * @author lilin
 *
 */
class WxWebAuthService extends BaseService
{
    /**
     * 检验授权凭证（access_token）是否有效
     *
     * @param string $accessToken
     * @param string $openid
     * @return mixed
     */
    static public function validationAccessToken($accessToken, $openid)
    {
        // 获取远程数据
        $url = __('url.auth_validation_access_token',['access_token'=>$accessToken, 'openid'=>$openid]);
        $info = self::sendRequest($url);
        
        return $info;
    }
    
    /**
     * 拉取用户信息(需scope为 snsapi_userinfo)
     *
     * @param string $openid
     * @return mixed
     */
    static public function getUserInfo($accessToken, $openid)
    {
        // 获取远程数据
        $url = __('url.auth_get_userinfo',['access_token'=>$accessToken, 'openid'=>$openid]);
        $info = self::sendRequest($url);

        if (isset($info['errcode'])){
            Log::info('获取微信用户信息出错:'.json_encode($info));
            return self::returnCode('sys.wx_access_token_error');
        }
        
        return self::returnCode('sys.success', $info);
    }
    
    /**
     * 刷新access_token 
     * 
     *  引入微信文档 
     *      由于access_token拥有较短的有效期，当access_token超时后，可以使用refresh_token进行刷新
     *      refresh_token有效期为30天，当refresh_token失效之后
     *      需要用户重新授权。
     *
     * @param string $refreshToken
     * @return mixed
     */
    static public function getAuthRefreshToken($refreshToken)
    {
        // 获取远程数据
        $url = __('url.auth_refresh_token',['appid'=>config('console.appId'),'refresh_token'=>$refreshToken]);
        $info = self::sendRequest($url);
        
        if (isset($info['errcode'])){
            Log::info('微信认证使用refresh_token请求时错误:'.json_encode($info));
            return self::returnCode('sys.wx_refresh_token_error', $info);
        }
        
        // openid为key存入redis
        $accessTokenKey = config('console.redis_key.auth_access_token').$info['openid'];
        Redis::set($accessTokenKey, $info['access_token']);
        Redis::EXPIRE($accessTokenKey, $info['expires_in']); //7200秒
        
        $result = ['access_token'=>$info['access_token'],'openid'=>$info['openid']];
        
        return self::returnCode('sys.success', $result);
    }
    
    /**
     * 通过code换取网页授权access_token
     *
     * @param string $authCode 微信返回的code
     * @param int    $openId   微信用户的openId 如果有,则表示通过openId获取
     * 
     * @return array
     */
    static public function getAuthAccessCode($authCode, $openId = 0)
    {
        if (!$openId){
            // 获取远程数据
            $url = __('url.auth_get_access_token',['appid'=>config('console.appId'),'secret'=>config('console.appSecret'),'code'=>$authCode]);
            $info = self::sendRequest($url);
            if (isset($info['errcode'])){
                Log::info('微信通过code获取accessToken出错:'.json_encode($info));
                return self::returnCode('sys.wx_code_error');
            }
            
            $accessTokenKey = config('console.redis_key.auth_access_token').$info['openid'];
            $refreshTokenKey = config('console.redis_key.auth_refresh_token').$info['openid'];
            
            // openid为key存入redis
            Redis::set($accessTokenKey, $info['access_token']);
            Redis::EXPIRE($accessTokenKey, $info['expires_in']); //默认7200
            
            
            Redis::set($refreshTokenKey, $info['refresh_token']);
            Redis::EXPIRE($refreshTokenKey, 30*24*60*60); //30天
            
            $result = ['access_token'=>$info['access_token'],'openid'=>$info['openid']];
            
        }else{
            $info = self::_getAuthAccessByOpenId($openId);
            if ($info['code'] != self::SUCCESS_CODE){
                return $info;
            }
            
            $result = ['access_token'=>$info['data']['access_token'],'openid'=>$info['data']['openid']];
        }
        
        return self::returnCode('sys.success', $result);
    }
    
    /**
     * 通过微信的openID获取accessToken
     *
     * @param string $openId
     * @return array
     */
    static private function _getAuthAccessByOpenId($openId)
    {
        $accessTokenKey = config('console.redis_key.auth_access_token').$openId;
        $accessToken    = Redis::GET($accessTokenKey);
        
        if (!$accessToken){ //如果没有通过 refreshToken 重新获取
            
            //获取 refreshToken
            $refreshTokenKey    = config('console.redis_key.auth_refresh_token').$openId;
            $refreshToken       = Redis::GET($refreshTokenKey);
            
            if (!$refreshToken){ //如果没有找到refreshToken 提示前端要求重新授权
                Log::info('微信通过openid获取用户accessToken,refreshTtokenf过期,要求重新授权,openId:'.$openId);
                return self::returnCode('sys.weChatNeedsToBeReauthorized');
            }else{ //如果找到refreshToken,则通过刷新,重新生成一个accessToken
                $info = self::getAuthRefreshToken($refreshToken);
                if ($info['code'] != self::SUCCESS_CODE){
                    return $info;
                }
                $accessToken = $info['data']['access_token'];
            }
        }
        
        $data = ['access_token'=>$accessToken,'openid'=>$openId];
        
        return self::returnCode('sys.success', $data);
    }
    
    /**
     * 获取code
     *
     * @param string $request
     * @return mixed
     */
    static public function getAuthCode($request)
    {
        return $request['code'];
    }
    
    /**
     * 第一步：用户同意授权
     *
     * @return string
     */
    static public function getAuthUrl()
    {
        return __('url.auth_get_code',['appid'=>config('console.appId'),'redirect_uri'=>urlencode(config('console.web_index')),'scope'=>'snsapi_userinfo','state'=>'chwl']);
    }
}