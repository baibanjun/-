<?php
namespace App\Services\Interfaces\Wx;

/**
 * 这里是说明
 *
 * @author lilin
 *         wx(tel):13408099056
 *         qq:182436607
 *        
 */
interface WxInterface
{
    /**
     * 获取JsapiTicket
     * @return array
     */
    public function getJsapiTicket();

    /**
     * 获取二维码
     *
     * @param array $info
     *            二维码信息 ['type' => 'user','id' => 3];
     * @param int $expireSeconds
     *            过期时间 0(秒)永不过期
     * @return string
     */
    public function getQrcode(array $info, $expireSeconds = 0);

    /**
     * 公众号获取用户信息有别于网页授权获取
     *
     */
    public function getUserInfo($openid);
    
    /**
     * 获取access_token
     * @return array
     */
    public static function getAccessToken();
    
    /**
     * 设置菜单
     * @return array
     */
    public function setMenu();
}

