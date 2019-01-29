<?php
namespace App\Services\Interfaces\Wx;

/**
 * 微信推送事件
 *
 * @author lilin
 *         wx(tel):13408099056
 *         qq:182436607
 *        
 */
interface WxEventInterface
{
    /**
     * 接收文字信息
     *
     */
    public function msgText(array $eventData);
    
    /**
     * 接收语音消息
     *
     */
    public function msgVoice();
    
    /**
     * 接收视频消息
     *
     */
    public function msgVideo();
    
    /**
     * 接收小视频消息
     *
     */
    public function msgShortvideo();
    
    /**
     * 接收地理位置消息
     *
     */
    public function msgLocation();
    
    /**
     * 接收链接消息
     *
     */
    public function msgLink();
    
    /**
     * 关注/取消关注事件
     *
     */
    public function eventSubscribe(array $eventData);
    
    /**
     * 扫描带参数二维码事件
     *
     */
    public function eventQrCode(array $eventData);
    
    /**
     * 上报地理位置事件
     *
     */
    public function eventLocation();
    
    /**
     * 自定义菜单事件
     *
     */
    public function eventClick(array $eventData);
    
    /**
     * 回复文字信息
     *
     * @param string $toUsername
     * @param string $content
     */
    public function repText($toUsername, $content);
    
    /**
     * 回复图片
     *
     * @param string $toUsername
     * @param string $mediaId
     */
    public function repImg($toUsername, $mediaId);
}

