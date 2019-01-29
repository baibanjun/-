<?php
namespace App\Services;

use Illuminate\Support\Facades\Storage;
use App\Models\SysArea;
use App\Models\ProductCity;

/**
 * 城市编码
 *
 * @author lilin
 *         wx(tel):13408099056
 *         qq:182436607
 *        
 */
class AreaService extends BaseService
{
    protected static $areaFileData = [
        '3' => [
            'fileName' => 'three_area.json',
            'keyName' => 'threeSelectData',
            'areaTitle' => [
                [
                    'name' => '省份',
                    'code' => '1000000',
                    'items' => [
                        [
                            'name' => '城市',
                            'code' => '1000000',
                            'items' => [
                                [
                                    'name' => '区县',
                                    'code' => '1000000'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ],
        '4' => [
            'fileName' => 'four_area.json',
            'keyName' => 'fourSelectData',
            'areaTitle' => [
                [
                    'name' => '省份',
                    'code' => '1000000',
                    'items' => [
                        [
                            'name' => '城市',
                            'code' => '1000000',
                            'items' => [
                                [
                                    'name' => '区县',
                                    'code' => '1000000',
                                    'items' => [
                                        [
                                            'name' => '乡镇',
                                            'code' => '1000000'
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ];
    
    /**
     * 获取所有设置的城市
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Models\ProductCity[]
     */
    public static function getCity()
    {
        $list = ProductCity::all(['id','city_code','city_name']);
        
        return self::returnCode('sys.success', $list);
    }

    /**
     * 保存地区json数据
     *
     * @param number $lvl
     *            地区级别
     */
    static public function saveSysAreaJsonFile($lvl = 3)
    {
        $result = false;
        
        $areas = SysArea::orderBy('lvl')->orderBy('code')->get();
        
        if (! $areas->isEmpty()) {
            $data = self::_getAreaData($areas->toArray(), $lvl);
            
            $result = self::_saveJsonFile($data, $lvl);
        }
        
        return $result ? self::returnCode('sys.success') : self::returnCode('sys.saveJsonFileFailed');
    }

    /**
     * 保存Json文件
     *
     * @param integer $lvl
     */
    static private function _saveJsonFile($data, $lvl, $type = 'save')
    {
        $fileData = $data;
        
        if ($type == 'save') {
            $result = json_encode($fileData, JSON_UNESCAPED_UNICODE);
            
            $result = Storage::disk('public_json')->put(self::$areaFileData[$lvl]['fileName'], $result);
            
            return $result;
        } else {
            return $fileData;
        }
    }

    /**
     * 获取无效级分类数据
     *
     * @param array $data
     * @param number $getLvl
     * @param int $pCode
     * @param number $lvl
     */
    static private function _getAreaData($data, $getLvl = 3, $pCode = NULL, $lvl = 1)
    {
        $arr = [];
        
        foreach ($data as $k => $v) {
            if ($v['p_code'] == $pCode) {
                
                if ($lvl == $getLvl) {
                    $arr[$v['code']] = [
                        'name' => $v['name'],
                        'code' => $v['code'],
                    ];
                } else {
                    $arr[$v['code']] = [
                        'name' => $v['name'],
                        'code' => $v['code'],
                    ];
                    
                    unset($data[$k]);
                    
                    $child = self::_getAreaData($data, $getLvl, $v['code'], $lvl + 1);
                    $child = array_values($child);
                    
                    $arr[$v['code']]['items'] = $child;
                }
            }
        }
        
        $arr = array_values($arr);
        
        return $arr;
    }

    /**
     * 根据地区编码获取地区信息
     *
     * @param int $areaCode
     */
    static public function getAreaByCode($areaCode)
    {
        $areaCodeArr = self::getAreaCodeArr($areaCode);
        
        $areas = SysArea::whereIn('code', $areaCodeArr)->orderBy('lvl')->get([
            'code',
            'name',
            'lvl'
        ]);
        
        return $areas;
    }

    /**
     * 根据地区编码获取父级所有地区编码
     *
     * @param int $areaCode
     *
     * @return array
     */
    static protected function getAreaCodeArr($areaCode)
    {
        $result = [];
        
        if (is_array($areaCode)) {
            foreach ($areaCode as $code) {
                $result = array_merge($result, self::getAreaCodeArr($code));
            }
        } else {
            $result = [
                $areaCode,
                substr($areaCode, 0, 6) . '000',
                substr($areaCode, 0, 4) . '00000',
                substr($areaCode, 0, 2) . '0000000'
            ];
        }
        
        return array_unique($result);
    }
}