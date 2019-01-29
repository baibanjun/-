<?php
namespace App\Services;

use Illuminate\Support\Facades\Redis;
use Illuminate\Contracts\Logging\Log;
use App\Services\Wx\WxToolService;
use App\Services\Interfaces\Wx\WxInterface;
use App\Models\LotteryDraw;

/**
 * 微信
 *
 * @author lilin
 *
 */
class WeixinService extends WxToolService implements WxInterface
{
    /**
     * {@inheritDoc}
     * @see \App\Services\Interfaces\Wx\WxInterface::getJsapiTicket()
     */
    public function getJsapiTicket()
    {
        $redisKey = config('console.redis_key.jsapi_ticket');
        
        $ticket = Redis::GET($redisKey);
        
        if (! $ticket) {
            $url = __('url.jsapi_ticket', ['access_token' => self::getAccessToken()]);
            $info = self::sendRequest($url);
            
            if (isset($info['errcode']) && $info['errmsg'] == 'ok') {
                Redis::SET($redisKey, $info['ticket']);
                Redis::EXPIRE($redisKey, $info['expires_in']);
                
                $ticket = $info['ticket'];
            } else {
                Log::info('jsapiTicket 获取失败,报错信息:' . json_encode($info));
                return self::returnCode('sys.fail');
            }
        }
        
        return self::returnCode('sys.success', ['ticket'=>$ticket]);
    }
    
    /**
     * {@inheritDoc}
     * @see \App\Services\Interfaces\Wx\WxInterface::getQrcode()
     */
    public function getQrcode(array $info, $expireSeconds = 0)
    {
        if ($expireSeconds){ //短期二维码
            $parameters = [
                'expire_seconds'    => $expireSeconds,
                'action_name'       => 'QR_SCENE',
                'action_info'       => ['scene'=>['scene_str'=>json_encode($info)]]
            ];
            //二维码redis key
            $key = 'qr_scene:'.$info['type'].':'.$info['id'];
        }else{ //长期二维码
            $parameters = [
                'action_name'       => 'QR_LIMIT_STR_SCENE',
                'action_info'       => ['scene'=>['scene_str'=>json_encode($info)]]
            ];
            //二维码redis key
            $key = 'qr:limit_scene:'.$info['type'].':'.$info['id'];
        }

        $pic = Redis::get($key);
        
        if (!$pic){
            $url= __('url.qrcode',['access_token'=>self::getAccessToken()]);
            $info = self::sendRequest($url, ['json'=>$parameters], 'POST');
            $pic = $info['ticket'];
            
            Redis::SET($key,$pic);
            !$expireSeconds ? : Redis::EXPIRE($key, $info['expire_seconds']);
        }
        
        return $pic;
    }
    
    /**
     * {@inheritDoc}
     * @see \App\Services\Interfaces\Wx\WxInterface::getAccessToken()
     */
    public static function getAccessToken()
    {
        // 获取redis
        $key = config('console.redis_key.access_token');
        $redisAccessToken = Redis::get($key);
        
        // 不存在通过接口请求并加入redis
        if (!$redisAccessToken){
            // 远程获取
            $url= __('url.accessTokenUrl',['APPID'=>config('console.appId'),'APPSECRET'=>config('console.appSecret')]);
            $getAccessToken = self::sendRequest($url);
            // 设置access_token
            Redis::SET($key, $getAccessToken['access_token']);
            Redis::EXPIRE($key, $getAccessToken['expires_in']);
            
            // 重新赋值
            $redisAccessToken = $getAccessToken['access_token'];
        }
        
        return $redisAccessToken;
    }
    
    /**
     * {@inheritDoc}
     * @see \App\Services\Interfaces\Wx\WxInterface::getUserInfo()
     */
    public function getUserInfo($openid)
    {
        // 远程获取
        $url= __('url.user_info',['openid'=>$openid,'access_token'=>self::getAccessToken()]);
        return self::sendRequest($url);
    }

    /**
     * {@inheritDoc}
     * @see \App\Services\Interfaces\Wx\WxInterface::setMenu()
     */
    public function setMenu()
    {
        $config = config('wxmenu');
        
        $url= __('url.createMenuUrl',['access_token'=>self::getAccessToken()]);
        
        $lottery = LotteryDraw::where('status', LotteryDraw::STATUS_2)->get(['id','title']);
        
        $subButton = [];
        foreach ($lottery as $data) {
            $subButton[] = [
                "type" => "view",
                "name" => substr($data->title, 0,6),
                "url" => env('WEB_INDEX') . 'lottery?id=' . $data->id
            ];
        }
        
        $config['button'][1]['sub_button'] = $subButton;

        $menu = json_encode($config,JSON_UNESCAPED_UNICODE);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $menu);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        self::setLog('设置菜单',0, $tmpInfo);
        return $tmpInfo;
    }
}