<?php
namespace App\Services\Wx;

use App\Services\Interfaces\Wx\WxDecryptInterface;

/**
 * 这里是说明
 *
 * @author lilin
 *         wx(tel):13408099056
 *         qq:182436607
 *        
 */
class WxDecryptService extends WxToolService implements WxDecryptInterface
{
    /**
     * {@inheritDoc}
     * @see \App\Services\Interfaces\Wx\WxDecryptInterface::decryptMsg()
     */
    public function decryptMsg($array, $msgSignature, $timestamp = null, $nonce)
    {

        if (strlen($this->encodingAesKey) != 43) {
            // return ErrorCode::$IllegalAesKey;
        }
        
        // 提取密文
        if ($timestamp == null) {
            $timestamp = time();
        }
        
        $encrypt = $array['Encrypt'];
        
        // 验证安全签名
        $signature = $this->getSHA1($timestamp, $nonce, $encrypt);

        if ($signature != $msgSignature) {
            return '不相等 signature='.$signature.'msgSignature='.$msgSignature;
        }
        
        return $this->decrypt($encrypt, $this->appid);
    }

    /**
     * {@inheritDoc}
     * @see \App\Services\Interfaces\Wx\WxDecryptInterface::decrypt()
     */
    public function decrypt($encrypted)
    {
        $key = $this->_makeKey();
        
        $ciphertext_dec = base64_decode($encrypted);
        $iv = substr($key, 0, 16);
        $decrypted = openssl_decrypt($ciphertext_dec, 'AES-256-CBC', $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);
        
        $result = $this->decode($decrypted);
        
        $content = substr($result, 16, strlen($result));
        $len_list = unpack("N", substr($content, 0, 4));
        $xml_len = $len_list[1];
        $xml_content = substr($content, 4, $xml_len);
        $from_appid = substr($content, $xml_len + 4);
        
        return self::xmlToArray($xml_content);
    }

    /**
     * {@inheritDoc}
     * @see \App\Services\Interfaces\Wx\WxDecryptInterface::decode()
     */
    public function decode($text)
    {
        $pad = ord(substr($text, - 1));
        if ($pad < 1 || $pad > 32) {
            $pad = 0;
        }
        return substr($text, 0, (strlen($text) - $pad));
    }

    /**
     * 合并Key
     *
     * @return string
     */
    private function _makeKey()
    {
        return base64_decode($this->encodingAesKey . "=");
    }
}

