<?php
namespace App\Services\Admin;

use App\Models\Product;
use App\Models\Distribution;
use App\Models\ProductStandard;
use App\Models\ProductCity;
use App\Models\Business;
use App\Models\SysArea;
use App\Services\UploadService;

class ProductService extends BaseService
{
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
        $product = Product::where('id',$productId)->first(['id','poster']);
        if (!$product || !isset($product->poster[0]['name'])){
            return self::returnCode('sys.dataDoesNotExist');
        }
        
        $img = config('console.pic_url').$product->poster[0]['name'];
        
        $url = config('console.web_index').'details?id='.$productId.'&f='.$first.'&s='.$second;
        
        return UploadService::addQrcodeToPic($url, $img);
    }
    
    /**
     * 删除产品
     *
     * @param integer $id
     *            产品id
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function deleteProduct($id)
    {
        $product = Product::find($id);
        
        if (! $product) {
            return self::returnCode('sys.dataDoesNotExist');
        }
        
        if ($product->status != Product::STATUS_HIDE) {
            return self::returnCode('sys.statusIsNotNormal');
        }
        
        $result = $product->delete();
        
        return $result ? self::returnCode('sys.success') : self::returnCode('sys.fail');
    }

    /**
     * 修改产品状态
     *
     * @param integer $id
     *            产品id
     * @param integer $status
     *            产品状态
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function updateProductStatus($id, $status)
    {
        // 判断状态
        if (! in_array($status, [
            Product::STATUS_ITEM_UPSHELF,
            Product::STATUS_SOLD_OUT,
            Product::STATUS_HIDE
        ])) {
            return self::returnCode('sys.dataFali');
        }
        
        $product = Product::find($id);
        
        if (! $product) {
            return self::returnCode('sys.dataDoesNotExist');
        }
        
        switch ($status) {
            case Product::STATUS_ITEM_UPSHELF:
                if (! in_array($product->status, [
                    Product::STATUS_SOLD_OUT,
                    Product::STATUS_HIDE
                ])) {
                    if ($product->is_countdown == Product::IS_COUNTDOWN_1 && (strtotime($product->updated_at) + $product->time_limit) < time()) {
                        $product->is_countdown = Product::IS_COUNTDOWN_0;
                    } else {
                        return self::returnCode('sys.statusIsNotNormal');
                    }
                }
                break;
            case Product::STATUS_SOLD_OUT:
                if (! in_array($product->status, [
                    Product::STATUS_ITEM_UPSHELF
                ])) {
                    return self::returnCode('sys.statusIsNotNormal');
                }
                break;
            case Product::STATUS_HIDE:
                if (! in_array($product->status, [
                    Product::STATUS_SOLD_OUT,
                    Product::STATUS_ITEM_UPSHELF
                ])) {
                    return self::returnCode('sys.statusIsNotNormal');
                }
                break;
        }
        
        $product->status = $status;
        $result = $product->save();
        
        return $result ? self::returnCode('sys.success') : self::returnCode('sys.fail');
    }

    /**
     * 获取产品城市
     *
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function getProductCity($field = ['*'])
    {
        $productCity = ProductCity::get($field);
        
        return self::returnCode('sys.success', $productCity);
    }

    /**
     * 修改产品
     *
     * @param integer $id
     *            产品id
     * @param array $productData
     *            产品数据
     * @param array $distributionData
     *            分销数据
     * @param array $standardData
     *            规格数据
     * @param array $otherData
     *            其他数据
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function updateProduct($id, $productData, $distributionData, $standardData)
    {
        // 判断商家是否正常
        $business = Business::where('id', $productData['business_id'])->where('status', Business::STATUS_NORMAL)->first();
        if (! $business) {
            return self::returnCode('admin.businessIsNotExistOrStatusFail');
        }
        
        // 判断地区编码是否正确
        $sysArea = SysArea::where('code', $productData['city_code'])->where('lvl', 2)->first();
        if (! $sysArea) {
            return self::returnCode('admin.cityCodeFail');
        }
        
        // time_limit = 05:01:01;
        if ($productData['is_countdown'] == Product::IS_YES) {
            $timeLimit = explode(':', $productData['time_limit']);
            $productData['time_limit'] = $timeLimit[0] * 3600 + $timeLimit[1] * 60 + $timeLimit[2];
            
            if ($productData['time_limit'] == 0) {
                return self::returnCode('sys.timeFail');
            }
        } else {
            $productData['time_limit'] = 0;
        }
        
        // 修改产品
        $product = Product::find($id);
        
        if (! $product) {
            return self::returnCode('sys.dataDoesNotExist');
        }
        
        // 判断产品状态
        if ($product->status != Product::STATUS_HIDE) {
            return self::returnCode('sys.statusIsNotNormal');
        }
        
        foreach ($productData as $key => $val) {
            $product->$key = $val;
        }
        
        $product->status = Product::STATUS_ITEM_UPSHELF;
        $result = $product->save();
        
        if ($result) {
            // 修改分销 class_type, type, value
            self::updateDistribution($distributionData, $product);
            
            // 创建产品规格 'name', 'sale_price', 'price', 'quantity_sold', 'onhand'
            self::updateStandard($standardData, $product->id);
        }
        
        return $result ? self::returnCode('sys.success') : self::returnCode('sys.fail');
    }

    /**
     * 修改规格数据
     *
     * @param array $standardData
     *            规格数据
     * @param integer $productId
     *            产品id
     * @return NULL[]
     */
    static protected function updateStandard($standardData, $productId)
    {
        if ($standardData) {
            $idArr = [];
            foreach ($standardData as $val) {
                if (isset($val['id']) && $val['id']) {
                    $idArr[] = $val['id'];
                    $standard = ProductStandard::where('id', $val['id'])->where('pid', $productId)->first();
                    if ($standard) {
                        $standard->name = $val['name'];
                        $standard->sale_price = $val['sale_price'];
                        $standard->price = $val['price'];
                        $standard->quantity_sold = $val['quantity_sold'];
                        $standard->onhand = $val['onhand'];
                        $standard->save();
                    }
                } else {
                    $val['pid'] = $productId;
                    $result = ProductStandard::create($val);
                    $idArr[] = $result->id;
                }
            }
            
            ProductStandard::whereNotIn('id', $idArr)->where('pid', $productId)->delete();
        } else {
            ProductStandard::where('pid', $productId)->delete();
        }
    }

    /**
     * 修改分销数据
     *
     * @param array $distributionData
     *            分销数据
     * @param integer $productId
     *            产品id
     * @return array
     */
    static protected function updateDistribution($distributionData, $product)
    {
        foreach ($distributionData as $val) {
            $distribution = Distribution::where('id', $val['id'])->where('pid', $product->id)
                ->where('class_type', $val['class_type'])
                ->first();
            
            if ($distribution) {
                $distribution->type = $val['type'];
                $distribution->value = ($val['type'] == Distribution::ALLOCATION_TYPE_PERCENT) ? ($val['value'] / 100) : $val['value'];
                $distribution->save();
            }
        }
    }

    /**
     * 创建产品
     *
     * @param array $productData
     *            产品数据
     * @param array $distributionData
     *            分销数据
     * @param array $standardData
     *            规格数据
     * @param array $otherData
     *            其他数据
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function createProduct($productData, $distributionData, $standardData)
    {
        // 判断商家是否正常
        $business = Business::where('id', $productData['business_id'])->where('status', Business::STATUS_NORMAL)->first();
        if (! $business) {
            return self::returnCode('admin.businessIsNotExistOrStatusFail');
        }
        
        // 判断地区编码是否正确
        $sysArea = SysArea::where('code', $productData['city_code'])->where('lvl', 2)->first();
        if (! $sysArea) {
            return self::returnCode('admin.cityCodeFail');
        }
        
        // time_limit = 05:01:01;
        if ($productData['is_countdown'] == Product::IS_YES) {
            $timeLimit = explode(':', $productData['time_limit']);
            $productData['time_limit'] = $timeLimit[0] * 3600 + $timeLimit[1] * 60 + $timeLimit[2];
            
            if ($productData['time_limit'] == 0) {
                return self::returnCode('sys.timeFail');
            }
        } else {
            $productData['time_limit'] = 0;
        }
        
        $productData['primary_distribution_id'] = 0;
        $productData['secondary_distribution_id'] = 0;
        $productData['team_distribution_id'] = 0;
        
        // 创建产品
        $product = Product::create($productData);
        if ($product) {
            // 创建分销 class_type, type, value
            $product->distribution = self::createDistribution($distributionData, $product);
            
            // 创建产品规格 'name', 'sale_price', 'price', 'quantity_sold', 'onhand'
            $product->standard = self::createStandard($standardData, $product->id);
        }
        
        return $product ? self::returnCode('sys.success', $product) : self::returnCode('sys.fail');
    }

    /**
     * 创建规格数据
     *
     * @param array $standardData
     *            规格数据
     * @param integer $productId
     *            产品id
     * @return NULL[]
     */
    static protected function createStandard($standardData, $productId)
    {
        $resultData = [];
        foreach ($standardData as $val) {
            $val['pid'] = $productId;
            $resultData[] = ProductStandard::create($val);
        }
        
        return $resultData;
    }

    /**
     * 创建分销数据
     *
     * @param array $distributionData
     *            分销数据
     * @param integer $productId
     *            产品id
     * @return array
     */
    static protected function createDistribution($distributionData, $product)
    {
        $resultData = [];
        foreach ($distributionData as $val) {
            $val['pid'] = $product->id;
            $val['value'] = ($val['type'] == Distribution::ALLOCATION_TYPE_PERCENT) ? ($val['value'] / 100) : $val['value'];
            $result = Distribution::create($val);
            $resultData[] = $result;
            switch ($val['class_type']) {
                case Distribution::CLASS_TYPE_PRIMARY_DISTRIBUTION:
                    $product->primary_distribution_id = $result->id;
                    break;
                case Distribution::CLASS_TYPE_SECONDARY_DISTRIBUTION:
                    $product->secondary_distribution_id = $result->id;
                    break;
                case Distribution::CLASS_TYPE_TEAM_DISTRIBUTION:
                    $product->team_distribution_id = $result->id;
                    break;
            }
            $product->save();
        }
        
        return $resultData;
    }

    /**
     * 获取产品列表
     *
     * @param array $search
     *            搜索条件
     * @param array $field
     *            查询字段
     * @param number $limit
     *            每页显示条数
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function getProductList($search, $field = ['*'], $limit = 10)
    {
        $products = Product::where(function ($query) use ($search) {
            // 按产品名搜索
            if (isset($search['name']) && $search['name']) {
                $query->where('name', 'like', '%' . $search['name'] . '%');
            }
            // 按产品类型搜索
            if (isset($search['type']) && $search['type'] != 'all') {
                $query->where('type', $search['type']);
            }
            // 按产品类型搜索
            if (isset($search['search_status'])) {
                switch ($search['search_status']) {
                    case 'buying': // 抢购中
                        $query->where('status', Product::STATUS_ITEM_UPSHELF);
                        $query->where('is_countdown', Product::IS_NO);
                        $query->whereHas('standards', function ($query) {
                            $query->where('onhand', '>', 0);
                        });
                        break;
                    case 'count_down': // 倒计时
                        $query->where('status', Product::STATUS_ITEM_UPSHELF);
                        $query->where('is_countdown', Product::IS_YES);
                        $query->whereHas('standards', function ($query) {
                            $query->where('onhand', '>', 0);
                        });
                        $query->whereRaw('UNIX_TIMESTAMP(`updated_at`) + `time_limit` >= UNIX_TIMESTAMP(now())');
                        break;
                    case 'sold_out': // 已售罄
                        $query->where('status', Product::STATUS_ITEM_UPSHELF);
                        $query->whereDoesntHave('standards', function ($query) {
                            $query->where('onhand', '>', 0);
                        });
                        $query->where(function ($query) {
                            $query->where(function ($query) {
                                $query->where('is_countdown', Product::IS_NO);
                            });
                            $query->orWhere(function ($query) {
                                $query->where('is_countdown', Product::IS_YES);
                                $query->whereRaw('UNIX_TIMESTAMP(`updated_at`) + `time_limit` >= UNIX_TIMESTAMP(now())');
                            });
                        });
                        break;
                    case 'unline': // 已下架
                        $query->where(function ($query) {
                            $query->where('status', Product::STATUS_SOLD_OUT);
                            $query->orWhere(function ($query) {
                                $query->where('status', Product::STATUS_ITEM_UPSHELF);
                                $query->where('is_countdown', Product::IS_YES);
                                $query->whereRaw('UNIX_TIMESTAMP(`updated_at`) + `time_limit` < UNIX_TIMESTAMP(now())');
                            });
                        });
                        break;
                    case 'hidden': // 已隐藏
                        $query->where('status', Product::STATUS_HIDE);
                        break;
                }
            }
        })->with([
            'business' => function ($query) {
                $query->select('id', 'name', 'tel', 'address');
            },
            'sysArea' => function ($query) {
                $query->select('code', 'name');
            },
            'standards' => function ($query) {
                $query->select('id', 'pid', 'name', 'sale_price', 'price', 'quantity_sold', 'onhand');
            },
            'primarydDistribution' => function ($query) {
                $query->select('id', 'class_type', 'type', 'value');
            },
            'secondaryDistribution' => function ($query) {
                $query->select('id', 'class_type', 'type', 'value');
            },
            'teamDistribution' => function ($query) {
                $query->select('id', 'class_type', 'type', 'value');
            }
        ])
            ->whereHas('business', function ($query) use ($search) {
            // 按商家名称搜索
            if (isset($search['bus_name']) && $search['bus_name']) {
                $query->where('name', 'like', '%' . $search['bus_name'] . '%');
            }
        })
            ->whereHas('sysArea', function ($query) use ($search) {
            // 按城市
            if (isset($search['city_name']) && $search['city_name']) {
                $query->where('name', 'like', '%' . $search['city_name'] . '%');
            }
        })
            ->orderBy('id', 'DESC')
            ->paginate($limit, $field);
        
        return self::returnCode('sys.success', $products);
    }
}