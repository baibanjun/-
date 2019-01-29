<?php
namespace App\Services\Interfaces\Wx;

/**
 * 微信H5认证
 *
 * @author lilin
 *         wx(tel):13408099056
 *         qq:182436607
 *        
 */
interface WxWebAuthInterface
{

    /**
     * 检验授权凭证（access_token）是否有效
     *
     * @param string $accessToken
     * @param string $openid
     * @return mixed
     */
    public function validationAccessToken($accessToken, $openid);

    /**
     * 拉取用户信息(需scope为 snsapi_userinfo)
     *
     * @param string $openid
     * @return mixed
     */
    public function getUserInfo($accessToken, $openid);

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
    public function getAuthRefreshToken($refreshToken);

    /**
     * 通过code换取网页授权access_token
     *
     * @param string $authCode 微信返回的code
     * @param int    $openId   微信用户的openId 如果有,则表示通过openId获取
     *
     * @return array
     */
    public function getAuthAccessCode($authCode, $openId = 0);

    /**
     * 通过微信的openID获取accessToken
     *
     * @param string $openId
     * @return array
     */
    public function getAuthAccessByOpenId($openId);

    /**
     * 获取code
     *
     * @param string $request
     * @return mixed
     */
    public function getAuthCode($request);

    /**
     * 第一步：用户同意授权
     *
     * @return string
     */
    public function getAuthUrl();
}

