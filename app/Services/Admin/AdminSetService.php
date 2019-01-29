<?php
namespace App\Services\Admin;

use App\Models\AdminSet;

class AdminSetService extends BaseService
{

    /**
     * 根据类型名称获取后台设置
     *
     * @param string $typeName
     *            类型名称
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function getAdminSetByTypeName($typeName)
    {
        $adminSet = AdminSet::where('type_name', $typeName)->first();
        
        return self::returnCode('sys.success', $adminSet);
    }

    /**
     * 根据ID和类型名称修改后台设置
     * 
     * @param integer $id
     *            数据id
     * @param string $typeName
     *            类型名称
     * @param array $data
     *            数组值
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function updateAdminSet($id, $typeName, $data)
    {
        //数据检查
        switch ($typeName)
        {
            case 'attention':
                if (!isset($data['money']))
                {
                    return self::returnCode('sys.dataFali');
                }
                break;
            case 'withdrawal_prompt':
                if (!isset($data['content']))
                {
                    return self::returnCode('sys.dataFali');
                }
                break;
            case 'team_setting':
                if (!isset($data['team_number']) || !isset($data['sale_team_number']))
                {
                    return self::returnCode('sys.dataFali');
                }
                break;
            case 'lottery_draw':
                if (!isset($data['day_has_num']) || !isset($data['day_share_num']) || !isset($data['share_get_num']))
                {
                    return self::returnCode('sys.dataFali');
                }
                break;
            case 'weichat_group':
                if (!isset($data['group_name']) || !isset($data['group_title']) || !isset($data['group_qr_code']))
                {
                    return self::returnCode('sys.dataFali');
                }
                break;
            case 'business_enter_attention':
                if (!isset($data['pass_attention']) || !isset($data['return_attention']))
                {
                    return self::returnCode('sys.dataFali');
                }
                break;
        }

        $adminSet = AdminSet::where('id', $id)->where('type_name', $typeName)->first();
        
        if (! $adminSet) {
            return self::returnCode('sys.dataDoesNotExist');
        }
        
        $adminSet->value = $data;
        
        $result = $adminSet->save();
        
        return $result ? self::returnCode('sys.success') : self::returnCode('sys.fail');
    }
}