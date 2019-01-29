<?php
namespace App\Services;

use App\Models\NewsInfo;

/**
 * 攻略,2台,探店,新闻详情
 *
 * @author lilin
 *
 */
class NewsService extends BaseService
{
    
    /**
     * 获取新闻详情
     *
     * @param int $id
     * @param array $fields
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function getInfo($id, $fields = ['*'])
    {
        $data = NewsInfo::find($id, $fields);
        
        return self::returnCode('sys.success', $data);
    }
    
    /**
     * 新闻列表
     *
     * @param integer   $type       类别 1:攻略 2:2台 3:探店
     * @param string    $cityCode   城市编码
     * @param array     $fields     字段
     * @param integer   $page       页码
     * @param integer   $limit      显示条数
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function getList($type, $cityCode, $fields = ['*'], $page = 1, $limit = 20)
    {
        $list = NewsInfo::where('type', $type)->where('city_code', $cityCode)->orderBy('id', 'desc')->paginate($limit, $fields);
        
        return self::returnCode('sys.success', $list);
    }
}

