<?php
namespace App\Services\Wx;

use App\Services\BaseService;

/**
 * 微信工具
 *
 * @author lilin
 *         wx(tel):13408099056
 *         qq:182436607
 *        
 */
abstract class WxToolService extends BaseService
{
    public $appid;
    public $encodingAesKey;
    public $token;
    
    public function __construct()
    {
        $this->appid            = config('console.appid');
        $this->encodingAesKey   = config('console.wx_decrypt.encoding_aes_key');
        $this->token            = config('console.wx_decrypt.token');
    }
    
    /**
     * xml转成数据
     *
     * @param string $xml
     *
     * @return array
     */
    public static function xmlToArray($xml)
    {
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $values;
    }
    
    /**
     * 数组转成XML
     *
     * @param array $array
     * @return string
     */
    public static function arrayToXml(array $config)
    {
        $xml = "<xml>";
        foreach ($config as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }
    
    /**
     * 用SHA1算法生成安全签名
     * @param string $timestamp 时间戳
     * @param string $nonce 随机字符串
     * @param string $encrypt 密文消息
     */
    public static function getSHA1($timestamp, $nonce, $encrypt_msg)
    {
        $array = array($encrypt_msg, config('console.wx_decrypt.token'), $timestamp, $nonce);
        sort($array, SORT_STRING);
        $str = implode($array);
        return sha1($str);
    }
    
    /**
     * 这里是说明
     *
     * @param   string    $url
     * @param   array     $content
     * @return  array
     */
    public static function sendWxHttp($url, $content)
    {
        //转成微信需要的格式
        $content = json_encode($content,JSON_UNESCAPED_UNICODE);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        
        return json_decode($tmpInfo, true);
    }
}

