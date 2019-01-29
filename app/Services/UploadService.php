<?php
namespace App\Services;

use YueCode\Cos\QCloudCos;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

/**
 * 图片上传
 *
 * @author liugang
 *        
 */
class UploadService extends BaseService
{

    /**
     * 用户自定义海报
     *
     * @param string $headBase64Img 头像图片base64
     * @param string $userName      用户的姓名
     */
    static public function poster($headBase64Img, $userName)
    {
        $uid = Auth::id();
        self::setLog('生成海报',0, ['uid'=>$uid]);
        //是否关注了公众号
        $user = User::find($uid);
        if (!$user || $user->is_subscribe == User::IS_SUBSCRIBE_0){
            return self::returnCode('sys.dontAttention');
        }
        
        $redisKey = config('console.redis_key.upload_img') . md5($headBase64Img . $userName . $uid);
        
        $result = Redis::get($redisKey);
        
        if (!$result){
            // 生成二维
            $weixin = new WeixinService();
            $weixinQrCode = $weixin->getQrcode(['type' => 'user','id' => $uid]);

            $src = imagecreatetruecolor(145, 145);
            
            $qrImgSrc = env('QR_URL').$weixinQrCode;
            $size=getimagesize($qrImgSrc);
            $srcQr = imagecreatefromjpeg($qrImgSrc);
            imagecopyresampled($src,$srcQr, 0, 0, 0, 0, 145, 145, $size['0'], $size['1']);
            
            // 海报背景
            $posterBg = public_path('img/poster_bg.png');
            $picInfo = getimagesize($posterBg);
            $img = imagecreatefrompng($posterBg);
            imagecopymerge($img, $src, 299, 1177, 0, 0, 145, 145, 100);
            
            try {
                //加入头像
                self::_addHead($img, $headBase64Img);
                //加入文字
                self::_addText($img, $userName);
            } catch (\Exception $e) {
                return self::returnCode('sys.fail');
            }
            
//                     header("content-type:image/png");
//                     imagepng($img);

            // 本地存储路径
            $savePath = public_path('img/' . time() . rand() . '.png');
            imagepng($img, $savePath);
            
            imagedestroy($img);
            
            // 重新上传云服务器
            $result = json_encode(self::upload($savePath));
            
            // 删除本地图片
            if (file_exists($savePath)) {
                unlink($savePath);
            }
            
            Redis::set($redisKey, $result);
        }

        return self::returnCode('sys.success', json_decode($result));
    }
    
    /**
     * 加入头像
     *
     * @param $img
     */
    static private function _addHead(&$img, $headBase64Img)
    {   
        $base64_body = substr(strstr($headBase64Img,','),1);
        $data = base64_decode($base64_body);
        
        $headImg = imagecreatefromstring($data);
        
        imagecopymerge($img, $headImg, 161, 271, 0, 0, 440, 413, 100);
    }
    
    /**
     * 加入文字
     *
     * @param  $img
     */
    static private function _addText($img, $text)
    {
        //创建一个画布
        $fontImg = imagecreatetruecolor(239, 124);
        $zhibg = imagecolorallocatealpha($fontImg, 255, 0, 0,100);

        imagefill($fontImg,0,0,$zhibg);
        imagecolortransparent($fontImg,$zhibg);
        
        // 字体
        $fontfile = public_path('img/font.TTF');
        
        // 过滤emoji表情
        filterEmoji($text);
        
        // 字体大小
        $size = 32;
        // 设置字体颜色
        $black = imagecolorallocate($fontImg, 0, 0, 0);
        
        //将文字加入到画布
        imagettftext($fontImg, $size, 35, 65, 115, $black, $fontfile, $text);
        
        //打call背景图
        $callBgPath = public_path('img/call_bg.png');
        $callImg = imagecreatefrompng($callBgPath);
        
        //将文字加入到打call
        imagecopy($callImg, $fontImg, 0, 0, 0, 0, 165, 124);
        
        //将打call加入到海报
        imagecopy($img, $callImg, 148, 572, 0, 0, 468, 138);
    }
    
    /**
     * 给图片加二维码
     *
     * @param string $url
     *            二维码链接
     * @param string $img
     *            产品原图
     * @return array|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function addQrcodeToPic($url, $img)
    {
        $redisKey = config('console.redis_key.upload_img') . md5($url . $img);
        
        $result = Redis::get($redisKey);
        
        if (! $result) {
            $qrImg = QrCode::format('png')->margin(0)
                ->size(130)
                ->generate($url);
            $src = imagecreatefromstring($qrImg);
            
            $picInfo = getimagesize($img);
            
            if (! $picInfo) {
                return self::returnCode('sys.dataDoesNotExist');
            }
            
            $ext = explode('/', $picInfo['mime']);
            
            // 合并二维图片指定x,y
            switch ($ext[1]) {
                case 'png':
                    $img = imagecreatefrompng($img);
                    break;
                case 'jpeg':
                case 'jpg':
                    $img = imagecreatefromjpeg($img);
                    break;
                case 'gif':
                    $img = imagecreatefromgif($img);
                    break;
            }
            
            imagecopymerge($img, $src, 9, 1195, 0, 0, 130, 130, 100);
            
            // 本地存储路径
            $savePath = public_path('img/' . time() . rand() . '.png');
            imagepng($img, $savePath);
            
            imagedestroy($img);
            imagedestroy($src);
            
            // 重新上传云服务器
            $result = json_encode(self::upload($savePath));
            
            // 删除本地图片
            if (file_exists($savePath)) {
                unlink($savePath);
            }
            
            Redis::set($redisKey, $result);
        }
        
        return self::returnCode('sys.success', json_decode($result));
    }

    /**
     * 本地文件上传
     *
     * @param string $file
     * @param string $dst
     * @param number $returnIsArray
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function upload($file, $dst = 'product')
    {
        $picInfo = getimagesize($file);
        
        if (! $picInfo) {
            return [];
        }
        
        $fileName = time() . rand();
        $ext = explode('/', $picInfo['mime']);
        $dstPath = $dst . '/' . date('Ym') . '/' . $fileName . '.' . $ext[1];
        
        $ret = QCloudCos::upload(env('BUCKET'), $file, $dstPath);
        
        $ret = json_decode($ret, true);
        
        if ($ret['code'] == 0) {
            $data = [
                'name' => $dstPath,
                'width' => $picInfo[0],
                'height' => $picInfo[1],
                'mime' => $picInfo['mime']
            ];
            return $data;
        } else {
            return [];
        }
    }

    static public function uploadPic($file, $dst = 'product', $returnIsArray = 1)
    {
        $picInfo = getimagesize($file);
        
        if (! $picInfo) {
            return self::returnCode('sys.dataDoesNotExist');
        }
        
        $srcPath = $file->getPathname();
        $extension = $file->getClientOriginalExtension();
        $fileName = time() . rand();
        $dstPath = $dst . '/' . date('Ym') . '/' . $fileName . '.' . $extension;
        $size = $file->getSize();
        
        $ret = QCloudCos::upload(env('BUCKET'), $srcPath, $dstPath);
        
        $ret = json_decode($ret, true);
        
        if ($ret['code'] == 0) {
            if ($returnIsArray) {
                $data = [
                    'name' => $dstPath,
                    'width' => $picInfo[0],
                    'height' => $picInfo[1],
                    'mime' => $picInfo['mime'],
                    'size' => $size
                ];
            } else {
                $data = [
                    'src' => config('console.pic_url') . $dstPath,
                    'title' => ''
                ];
            }
            
            return self::returnCode('sys.success', $data);
        } else {
            return self::returnCode('sys.fail');
        }
    }
}