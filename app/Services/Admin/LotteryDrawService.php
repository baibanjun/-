<?php
namespace App\Services\Admin;

use App\Models\LotteryDraw;
use App\Models\LotteryDrawList;
use App\Jobs\setWxMenuJob;

class LotteryDrawService extends BaseService
{

    /**
     * 修改抽奖活动状态
     *
     * @param integer $id
     *            抽奖活动id
     * @param integer $status
     *            抽奖活动状态
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function updateLotteryDrawStatus($id, $status)
    {
        $lotteryDraw = LotteryDraw::find($id);
        
        if (! $lotteryDraw) {
            return self::returnCode('sys.dataDoesNotExist');
        }
        
        if ($lotteryDraw->status == $status) {
            return self::returnCode('sys.statusIsNotNormal');
        }
        
        if ($status == LotteryDraw::STATUS_2) {
            // 判断奖品总库存
            if ($lotteryDraw->surplus_number <= 0) {
                return self::returnCode('admin.lottoryDrawSurplusNumberFail');
            }
            // 判断已上架的抽奖活动数量
            $lotteryDrawCount = LotteryDraw::where('status', LotteryDraw::STATUS_2)->where('surplus_number', '>', 0)->count();
            
            if ($lotteryDrawCount >= 3) {
                return self::returnCode('admin.lottoryDrawHasEnough');
            }
        }
        
        $lotteryDraw->status = $status;
        
        if ($lotteryDraw->save()){
            dispatch((new setWxMenuJob())->onQueue('setwxmenu'));
            
            $result = self::returnCode('sys.success');
        }else{
            $result = self::returnCode('sys.fail');
        }
        
        return $result;
    }

    /**
     * 删除抽奖
     *
     * @param integer $id
     *            抽奖id
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function deleteLotteryDraw($id)
    {
        $lotteryDraw = LotteryDraw::find($id);
        
        if ($lotteryDraw->status != LotteryDraw::STATUS_1) {
            return self::returnCode('sys.statusIsNotNormal');
        }
        
        $result = $lotteryDraw->delete();
        
        return $result ? self::returnCode('sys.success') : self::returnCode('sys.fail');
    }

    /**
     * 获取抽奖
     *
     * @param array $search
     *            搜索条件
     * @param array $field
     *            获取字段
     * @param number $limit
     *            每页显示条数
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function getLotteryDraws($search, $field = ['*'], $limit = 10)
    {
        $datas = LotteryDraw::with([
            'business' => function ($query) {
                $query->select('id', 'name', 'tel', 'address');
            },
            'lotteryDrawList' => function ($query) {
                $query->select('id', 'lottery_draw_id', 'name', 'draw_type', 'inventory', 'has_send_num', 'probability', 'start_date', 'end_date', 'use_condition', 'pic', 'description', 'is_auto_hidden');
            }
        ])->where(function ($query) use ($search) {
            if (isset($search['title']) && $search['title']) {
                $query->where('title', 'like', '%' . $search['title'] . '%');
            }
            
            if (isset($search['business_name']) && $search['business_name']) {
                $query->whereHas('business', function ($query) use($search) {
                    $query->where('name', 'like', '%' . $search['business_name'] . '%');
                });
            }
            
            if (isset($search['lottery_type']) && $search['lottery_type'] != 'all') {
                $query->where('lottery_type', $search['lottery_type']);
            }
            
            if (isset($search['status']) && $search['status'] != 'all') {
                $query->where('status', $search['status']);
            }
        })
            ->orderBy('id', 'DESC')
            ->paginate($limit, $field);
        
        return self::returnCode('sys.success', $datas);
    }

    /**
     * 修改抽奖
     *
     * @param integer $id
     *            抽奖id
     * @param array $lotteryData
     *            抽奖数据
     * @param array $drawData
     *            奖品数据
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function updateLotteryDraw($id, $lotteryData, $drawData)
    {
        $lotteryDraw = LotteryDraw::find($id);
        
        if ($lotteryDraw->status != LotteryDraw::STATUS_1) {
            return self::returnCode('sys.statusIsNotNormal');
        }
        
        // 判断奖品数量
        switch ($lotteryDraw->lottery_type) {
            case LotteryDraw::LOTTERY_TYPE_1:
                if (count($drawData) != 8) {
                    return self::returnCode('admin.lottoryDrawNumberFail');
                }
                break;
            case LotteryDraw::LOTTERY_TYPE_2:
                if (count($drawData) != 6) {
                    return self::returnCode('admin.lottoryDrawNumberFail');
                }
                break;
        }
        
        $probability = 0;
        $inventory = 0;
        foreach ($drawData as $kk => $value) {
            $list{$kk} = LotteryDrawList::find($value['id']);
            
            if (! $list{$kk}) {
                return self::returnCode('admin.lottoryDrawListNotExist');
            }
            
            // 概率和
            $probability += $value['probability'];
            // 奖品总数量
            $inventory += $value['inventory'];
        }
        
        // 判断概率和是否等于1
        if ($probability != 1) {
            return self::returnCode('admin.lottoryDrawProbabilityFail');
        }
        
        $lotteryData['surplus_number'] = $inventory;
        
        foreach ($lotteryData as $lkey => $lottery) {
            $lotteryDraw->$lkey = $lottery;
        }
        
        $result = $lotteryDraw->save();
        
        if (! $result) {
            return self::returnCode('sys.fail');
        }
        
        foreach ($drawData as $key => $draw) {
            foreach ($draw as $k => $val) {
                $list{$key}->$k = $val;
            }
            $list{$key}->save();
        }
        
        return self::returnCode('sys.success');
    }

    /**
     * 新增抽奖
     *
     * @param array $lotteryData
     *            抽奖数据
     * @param array $drawData
     *            奖品数据
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function createLotteryDraw($lotteryData, $drawData)
    {
        // 判断奖品数量
        switch ($lotteryData['lottery_type']) {
            case LotteryDraw::LOTTERY_TYPE_1:
                if (count($drawData) != 8) {
                    return self::returnCode('admin.lottoryDrawNumberFail');
                }
                break;
            case LotteryDraw::LOTTERY_TYPE_2:
                if (count($drawData) != 6) {
                    return self::returnCode('admin.lottoryDrawNumberFail');
                }
                break;
        }
        
        $probability = 0;
        $inventory = 0;
        foreach ($drawData as $value) {
            // 概率和
            $probability += $value['probability'];
            // 奖品总数量
            $inventory += $value['inventory'];
        }
        
        // 判断概率和是否等于1
        if ($probability != 1) {
            return self::returnCode('admin.lottoryDrawProbabilityFail');
        }
        
        $lotteryData['surplus_number'] = $inventory;
        $result = LotteryDraw::create($lotteryData);
        
        if (! $result) {
            return self::returnCode('sys.fail');
        }
        
        foreach ($drawData as $key => $draw) {
            $draw['lottery_draw_id'] = $result->id;
            LotteryDrawList::create($draw);
        }
        
        return self::returnCode('sys.success');
    }
}