<?php
namespace App\Services\Interfaces\Wx;

/**
 * 微信解密
 *
 * @author lilin
 *         wx(tel):13408099056
 *         qq:182436607
 *        
 */
interface WxDecryptInterface
{
    /**
     * 解密主体
     *
     * @param array $array
     * @param string $msgSignature
     * @param string $timestamp
     * @param string $nonce
     * @return string
     */
    public function decryptMsg($array, $msgSignature, $timestamp = null, $nonce);

    /**
     * 对密文进行解密
     *
     * @param string $encrypted 需要解密的密文
     * @return string 解密得到的明文
     */
    public function decrypt($encrypted);
    
    /**
     * 对解密后的明文进行补位删除
     *
     * @param string $text 解密后的明文
     * @return string 删除填充补位后的明文
     */
    public function decode($text);
}

