<?php
namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Redis;
use App\Models\ProductStandard;

/**
 * 商品管理
 *
 * @author lilin
 *        
 */
class ProductService extends BaseService
{
    
    /**
     * 库存减少一定数量
     *
     * @param integer $standardId   规格id
     * @param integer $quantity     数量
     * @param integer $orderId      订单id
     */
    static public function decOnhand($standardId, $quantity, $orderId)
    {
        $dec = ProductStandard::where('id', $standardId)->decrement('onhand', $quantity);
        
        if ($dec) {
            $onhandKey = config('console.redis_key.wx_pay_onhand') . $standardId . '_orderid_' . $orderId;
            Redis::DEL($onhandKey);
            return true;
        }
        return false;
    }
    
    /**
     * 获取一个规格的预售数量
     *
     * @param   integer $standardId 规格id
     * @return  integer
     */
    static public function getBookingNumber($standardId)
    {
        $patternKey = config('console.redis_key.wx_pay_onhand') . $standardId;
        $keys = Redis::KEYS($patternKey . '*');
        
        $values = [];
        if (! empty($keys)) {
            $values = Redis::MGET($keys);
        }
        return array_sum($values);
    }
    
    /**
     * 产品图片二维码
     *
     * @param   string $productId
     * @param   string $first
     * @param   string $second
     * @return  array
     */
    static public function getPoster($productId, $first, $second)
    {
        $product = Product::where('id',$productId)->where('status', '<>', Product::STATUS_HIDE)->first(['id','business_id','poster']);
        if (!$product || !isset($product->poster[0]['name'])){
           return self::returnCode('sys.dataDoesNotExist');
        }
        
        $img = config('console.pic_url').$product->poster[0]['name'];
        
        $url = config('console.web_index').'details?id='.$productId.'&f='.$first.'&s='.$second;
        
        return UploadService::addQrcodeToPic($url, $img);
    }
    
    /**
     * 获取指定产品信息
     *
     * @param   int       $id
     * @param   array     $fields 
     * @return  array
     */
    static public function getInfo($id, $fields = ['*'])
    {
        $data = Product::with(['primaryDistribution'=>function($query){
            $query->select(['id', 'pid', 'type', 'value']);
        },'standards'=>function($query){
            $query->select(['id', 'pid','name','sale_price','price','quantity_sold','onhand']);
        },'business'=>function($query){
            $query->select(['id','name','tel','address','lng','lat']);
        }])->where('status', '<>', Product::STATUS_HIDE)->find($id, $fields);
        
        if (! $data || ! $data->standards || !$data->business) {
            return self::returnCode('sys.dataDoesNotExist');
        }
        
        //倒计时开启并结束时更改产品状态为已下架
        if ($data->status && $data->time_limit && $data->updated_at){
            $data->status = self::countdown($data->status, $data->time_limit, $data->updated_at);
        }
        
        $data->standards->each(function($standard){
            //实际库存减去,即将支付还未超时的库存数
            $onhand = $standard->onhand - self::getBookingNumber($standard->id);
            $standard->onhand = $onhand > 0 ? $onhand : 0;
        });
        
        return self::returnCode('sys.success', $data);
    }
    
    /**
     * 获取对应类别的产品列表
     *
     * @param   integer $type       类型
     * @param   string  $cityCode   城市编码
     * @param   integer $page       页码
     * @param   integer $limit      显示数
     * @return  array
     */
    static public function getList($type, $cityCode, $fields = ['*'], $page = 1, $limit = 20)
    {
        $list = Product::with(['primaryDistribution'=>function($query){
            $query->select(['id', 'pid', 'type', 'value']);
        },'standards'=>function($query){
            $query->select(['id', 'pid','name','sale_price','price','quantity_sold','onhand']);
        }])->where('type', $type)->where('city_code', $cityCode)->where('status', '<>', Product::STATUS_HIDE)->orderBy('id', 'desc')->paginate($limit, $fields);
        
        $list->each(function ($item) {
            $item->standards->each(function($standard){
                //实际库存减去,即将支付还未超时的库存数
                $onhand = $standard->onhand - self::getBookingNumber($standard->id);
                $standard->onhand = $onhand > 0 ? $onhand : 0;
            });
            
            //倒计时开启并结束时更改产品状态为已下架
                if ($item->status && $item->time_limit && $item->updated_at){
                    $item->status = self::countdown($item->status, $item->time_limit, $item->updated_at);
                }
        });
        
        return self::returnCode('sys.success', $list);
    }
    
    /**
     * 倒计时开启并结束时更改产品状态为已下架
     *
     * @param   int     $nowStatus  当前状态
     * @param   int     $timeLimit  倒计时时间(秒)
     * @param   string  $startTime  倒计时开启时间
     * @return  int
     */
    static public function countdown($nowStatus, $timeLimit, $startTime)
    {
        $status = $nowStatus;
        if ($nowStatus == Product::STATUS_ITEM_UPSHELF) {
            $endTime = strtotime($startTime) + $timeLimit;
            if ($endTime < time()) {
                $status = Product::STATUS_SOLD_OUT;
            }
        }
        return $status;
    }
}