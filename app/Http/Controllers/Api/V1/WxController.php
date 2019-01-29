<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use App\Services\WxPayService;
use App\Services\Wx\WxDecryptService;
use App\Services\Wx\WxEventService;
use App\Services\Wx\WxSendService;


class WxController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
//         Log::info('t来了');
//         Log::info($request->method());
//         Log::info($request->all());
        
        $xml = file_get_contents('php://input');
        
        Log::info($xml);
        
        $data = WxPayService::_xmlToArray($xml);
        
        Log::info($data);

//         $array = array(
//             'ToUserName' => 'gh_2ea872c45990',
//             'Encrypt' => '8ry89oaxY43ujLNMyUPJgAKPp3SE52FksM5GUAXnc01fcw/sg10zHpUtSaV94ptH1NLt5EHClz6JIPRQ5dSwIwUqitn5vt/SShPB0ikQ5m4VdWcuv+2UMi/IUv4+UCpm0+0uffnVx0awRkBRZ4dg9M6uOaIQ6664HMOb3qjRO17wNAv/RQTS7F8y8y2uHd+E3DoTTOoCKhNiEcsGbHOWmeGQdtsvs6KzEnfbhOB8rDWa76rAoLqmNSxv3OzD6+mFaH7cuGszr9kS5fMO6pQrBbfAqIlFs70yqyY1Om3PcuUQVITAG7Z6XDp6pBdk+NAyt2mmrN3J3WQyydy9t/Z7P9KenU+aSIVvYs3LHQyYYP771RhelSrEE9dXohZThomYu9Qe4jNeHR7rySuwRYXUVmjPlpfQe59/bWey77Ip87Za/pnEpZo3rgPotjPszd2shBeDTKuZ5YL47R/tR6dQZpMWPGkRgA6/bPfHIZIcUhyqVp6rPMzNfJilmZwBHsR1OSPb8eeIjOlI5YloDOrlK2atSS8vw8P3SYxHJJE3ulBz/8o7O2j6uEA59lw87aiU7RF/0dQQGvECRit24veNaOLVcq2KTX6OJxEnFAnPHokPfQy+gAoBiezxVmQ0cbkp'
//         );
        
//         $encodingAesKey = 'ltrZC85WMfHNLZgshMlVgeV9skNdk1SqpbRKomQ1189';
//         $token = 'lgameflangtechcom';
//         $msgSignature='d3ba89e419102ebf5ffe8c9c717d730d409ef776';
//         $nonce='1951944770';
//         $timestamp = '1542358445';
//         $appid='wx480c35c0277c0521';
        
//         $text = "<xml><ToUserName><![CDATA[oia2Tj我是中文jewbmiOUlr6X-1crbLOvLw]]></ToUserName><FromUserName><![CDATA[gh_7f083739789a]]></FromUserName><CreateTime>1407743423</CreateTime><MsgType><![CDATA[video]]></MsgType><Video><MediaId><![CDATA[eYJ1MbwPRJtOvIEabaxHs7TX2D-HV71s79GUxqdUkjm6Gs2Ed1KF3ulAOA9H1xG0]]></MediaId><Title><![CDATA[testCallBackReplyVideo]]></Title><Description><![CDATA[testCallBackReplyVideo]]></Description></Video></xml>";
        
//         Log::info('开始加密');
//         $encrypt = $this->encryptMsg($appid, $token, $text, $timestamp, $nonce);
//         Log::info($encrypt);
        
//         $array = WxPayService::_xmlToArray($encrypt);
        
//         Log::info('开始解密');
// //         $decrypt = $this->decryptMsg($array, $encodingAesKey, $token, $appid, $array['MsgSignature'], $timestamp, $nonce);

// //         $ttt = new WxDecryptService();
// //         return $ttt->decryptMsg($array, $msgSignature, $timestamp,$nonce);
        
// //         Log::info($decrypt);
       
        
        return $request->echostr;
        
    }

    public function store(Request $request)
    {
        Log::info('微信主动请求');
        
        Log::info($request->all());
        
        $xml = file_get_contents('php://input');
        Log::info($xml);
        
        Log::info('开始解密');
        $decrypt = new WxDecryptService();
        $array = $decrypt->xmlToArray($xml);
        $data = $decrypt->decryptMsg($array, $request->msg_signature, $request->timestamp, $request->nonce);
        Log::info($data);
        
        if ($data['MsgType'] == 'event'){
            $type = strtolower($data['MsgType'] . '_' . $data['Event']);
        }else{
            $type = $data['MsgType'];
        }
        
        $event = new WxEventService();
        
        $result = [];
        switch ($type) {
            case 'text':
                $result = $event->msgText($data);
                break;
            case 'event_scan':
            case 'event_subscribe':
                $result = $event->eventQrCode($data);
                //发送大黄蜂海报链接
                $send = new WxSendService();
                $send->sendTextMsg($data['FromUserName']);
                break;
            
            case 'event_unsubscribe':
                $result = $event->eventSubscribe($data);
                break;
            case 'event_click':
                $data['rep_content'] = '开发中,敬请期待!';
                $result = $event->eventClick($data);
                break;
        }
        
        Log::info('事件处理完成,结果:'.json_encode($result));
        
        return empty($result) ? 'success' : $result;
    }
    
    /**
     * 将公众平台回复用户的消息加密打包.
     * <ol>
     *    <li>对要发送的消息进行AES-CBC加密</li>
     *    <li>生成安全签名</li>
     *    <li>将消息密文和安全签名打包成xml格式</li>
     * </ol>
     *
     * @param $replyMsg string 公众平台待回复用户的消息，xml格式的字符串
     * @param $timeStamp string 时间戳，可以自己生成，也可以用URL参数的timestamp
     * @param $nonce string 随机串，可以自己生成，也可以用URL参数的nonce
     * @param &$encryptMsg string 加密后的可以直接回复用户的密文，包括msg_signature, timestamp, nonce, encrypt的xml格式的字符串,
     *                      当return返回0时有效
     *
     * @return int 成功0，失败返回对应的错误码
     */
    public function encryptMsg($appid,$token, $replyMsg, $timeStamp, $nonce)
    {
        $pc = base64_decode('ltrZC85WMfHNLZgshMlVgeV9skNdk1SqpbRKomQ1189' . "=");
        
        //加密
        $array = $this->encrypt($replyMsg, $appid);
        
        if ($timeStamp == null) {
            $timeStamp = time();
        }
        $encrypt = $array;
        
        //生成安全签名
        $array = $this->getSHA1($token, $timeStamp, $nonce, $encrypt);

        $signature = $array;
        
        //生成发送的xml
        $encryptMsg = $this->generate($encrypt, $signature, $timeStamp, $nonce);
        
        return $encryptMsg;
    }
    
    public function decryptMsg($array, $encodingAesKey, $token, $appid, $msgSignature, $timestamp = null, $nonce)
    {
        if (strlen($encodingAesKey) != 43) {
            //return ErrorCode::$IllegalAesKey;
        }
        
       $pc = base64_decode($encodingAesKey . "=");
        
        //提取密文
        if ($timestamp == null) {
            $timestamp = time();
        }
        
        $encrypt = $array['Encrypt'];
//         $touser_name = $array['ToUserName'];
        
        //验证安全签名
        $array = $this->getSHA1($token, $timestamp, $nonce, $encrypt);
        $ret = $array;
        
        $signature = $array;
        if ($signature != $msgSignature) {
            return '不相等';
        }
        
        return $result = $this->decrypt($encrypt, $appid);
    }
    
    /**
     * 对明文进行加密
     * @param string $text 需要加密的明文
     * @return string 加密后的密文
     */
    public function encrypt($text, $appid)
    {
        $key = base64_decode('ltrZC85WMfHNLZgshMlVgeV9skNdk1SqpbRKomQ1189' . "=");
        
        $random = $this->getRandomStr();
        $text = $random . pack("N", strlen($text)) . $text . $appid;
        $iv = substr($key, 0, 16);
        $text = $this->encode($text);
        $encrypted = openssl_encrypt($text, 'AES-256-CBC', $key, OPENSSL_RAW_DATA|OPENSSL_ZERO_PADDING, $iv);

        
        return base64_encode($encrypted);
    }
    
    /**
     * 对密文进行解密
     * @param string $encrypted 需要解密的密文
     * @return string 解密得到的明文
     */
    public function decrypt($encrypted, $appid)
    {
        $key = base64_decode('ltrZC85WMfHNLZgshMlVgeV9skNdk1SqpbRKomQ1189' . "=");
        
        $ciphertext_dec = base64_decode($encrypted);
        $iv = substr($key, 0, 16);
        $decrypted = openssl_decrypt($ciphertext_dec, 'AES-256-CBC', $key, OPENSSL_RAW_DATA|OPENSSL_ZERO_PADDING, $iv);
        
        $result = $this->decode($decrypted);
        
        $content = substr($result, 16, strlen($result));
        $len_list = unpack("N", substr($content, 0, 4));
        $xml_len = $len_list[1];
        $xml_content = substr($content, 4, $xml_len);
        $from_appid = substr($content, $xml_len + 4);
        
        return WxPayService::_xmlToArray($xml_content);
            
    }
    
    /**
     * 对需要加密的明文进行填充补位
     * @param $text 需要进行填充补位操作的明文
     * @return 补齐明文字符串
     */
    function encode($text)
    {
        $block_size = 32;
        $text_length = strlen($text);
        //计算需要填充的位数
        $amount_to_pad = 32 - ($text_length % 32);
        if ($amount_to_pad == 0) {
            $amount_to_pad = 32;
        }
        //获得补位所用的字符
        $pad_chr = chr($amount_to_pad);
        $tmp = "";
        for ($index = 0; $index < $amount_to_pad; $index++) {
            $tmp .= $pad_chr;
        }
        return $text . $tmp;
    }
    
    /**
     * 对解密后的明文进行补位删除
     * @param decrypted 解密后的明文
     * @return 删除填充补位后的明文
     */
    function decode($text)
    {
        
        $pad = ord(substr($text, -1));
        if ($pad < 1 || $pad > 32) {
            $pad = 0;
        }
        return substr($text, 0, (strlen($text) - $pad));
    }
    
    /**
     * 用SHA1算法生成安全签名
     * @param string $token 票据
     * @param string $timestamp 时间戳
     * @param string $nonce 随机字符串
     * @param string $encrypt 密文消息
     */
    public function getSHA1($token, $timestamp, $nonce, $encrypt_msg)
    {
        $array = array($encrypt_msg, $token, $timestamp, $nonce);
        sort($array, SORT_STRING);
        $str = implode($array);
        return sha1($str);
    }
    
    /**
     * 随机生成16位字符串
     * @return string 生成的字符串
     */
    function getRandomStr()
    {
        
        $str = "";
        $str_pol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($str_pol) - 1;
        for ($i = 0; $i < 16; $i++) {
            $str .= $str_pol[mt_rand(0, $max)];
        }
        return $str;
    }
    
    /**
     * 生成xml消息
     * @param string $encrypt 加密后的消息密文
     * @param string $signature 安全签名
     * @param string $timestamp 时间戳
     * @param string $nonce 随机字符串
     */
    public function generate($encrypt, $signature, $timestamp, $nonce)
    {
        $format = "<xml>
<Encrypt><![CDATA[%s]]></Encrypt>
<MsgSignature><![CDATA[%s]]></MsgSignature>
<TimeStamp>%s</TimeStamp>
<Nonce><![CDATA[%s]]></Nonce>
</xml>";
        return sprintf($format, $encrypt, $signature, $timestamp, $nonce);
    }
}
