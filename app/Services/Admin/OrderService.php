<?php
namespace App\Services\Admin;

use App\Models\Order;
use App\Models\UserAccountRecord;

class OrderService extends BaseService
{

    /**
     * 根据订单id获取订单信息
     *
     * @param integer $orderId
     *            订单id
     * @param array $field
     *            查询字段
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function getOrderInfoByOrderId($orderId, $field = ['*'])
    {
        $order = Order::withTrashed()->with([
            'business' => function ($query) {
                $query->select('id', 'name');
            },
            'accountRecord' => function ($query) {
                $query->select('uid', 'money', 'object_type', 'object_id');
            }
        ])
            ->find($orderId, $field);
        
        $order->product = [
            'id' => $order->extend->copy['id'],
            'name' => $order->extend->copy['name'],
            'subtitle' => $order->extend->copy['subtitle'],
            'primaryd_distribution' => $order->extend->copy['primaryd_distribution'],
            'secondary_distribution' => $order->extend->copy['secondary_distribution'],
            'team_distribution' => $order->extend->copy['team_distribution']
        ];
        
        $order->addHidden([
            'extend',
            'area_value'
        ]);
        
        return self::returnCode('sys.success', $order);
    }

    /**
     * 获取订单分销收入
     *
     * @param array $search
     *            搜索条件
     * @param array $field
     *            查询字段
     * @param number $limit
     *            每页显示条数
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function getOrderDistributions($search, $field = ['*'], $limit = 10, $isexport = 0)
    {
        $orders = Order::withTrashed()->with([
            'accountRecord' => function ($query) {
                $query->select('uid', 'money', 'object_type', 'object_id');
            },
            'accountRecord.userTalent' => function ($query) {
                $query->select('uid', 'mobile');
            },
            'user' => function ($query) {
                $query->select('id', 'nickname');
            }
        ])
            ->whereHas('accountRecord', function ($query) {
            $query->where('id', '>', 0);
        })
            ->where('status', Order::STATUS_COMPLETED)
            ->where(function ($query) use ($search) {
            // 购买开始时间搜索
            if (isset($search['start_pay_time']) && $search['start_pay_time']) {
                $query->where('pay_time', '>=', $search['start_pay_time']);
            }
            // 购买结束时间搜索
            if (isset($search['end_pay_time']) && $search['end_pay_time']) {
                $query->where('pay_time', '<=', $search['end_pay_time']);
            }
            // 订单号搜索
            if (isset($search['sn']) && $search['sn']) {
                $query->where('sn', $search['sn']);
            }
        })
            ->orderBy('id', 'DESC');
        
        if ($isexport) {
            $orders = $orders->get($field);
            
            return self::getOrderDistributionExcelArrayData($orders);
        } else {
            $orders = $orders->paginate($limit, $field);
            
            return self::returnCode('sys.success', $orders);
        }
    }

    /**
     * 获取EXCEL数据
     *
     * @param array $datas
     * @return \Illuminate\Support\Collection
     */
    static protected function getOrderDistributionExcelArrayData($datas)
    {
        if ($datas->isEmpty()) {
            return collect([]);
        }
        
        $orderData = [];
        
        foreach ($datas as $d) {
            // 一级分销
            $firstMobile = '--';
            $firstMoney = '--';
            
            $secondMobile = '--';
            $secondMoney = '--';
            
            $teamMobile = '--';
            $teamMoney = '--';
            
            if ($d->account_record) {
                foreach ($d->account_record as $dd) {
                    switch ($dd->object_type) {
                        case UserAccountRecord::OBJECT_TYPE_1:
                            $firstMobile = $dd->user_talent->mobile;
                            $firstMoney = $dd->money;
                            break;
                        case UserAccountRecord::OBJECT_TYPE_2:
                            $teamMobile = $dd->user_talent->mobile;
                            $teamMoney = $dd->money;
                            break;
                        case UserAccountRecord::OBJECT_TYPE_6:
                            $secondMobile = $dd->user_talent->mobile;
                            $secondMoney = $dd->money;
                            break;
                    }
                }
            }
            
            $orderData[] = [
                '订单号' => $d->sn,
                '买家昵称' => $d->user->nickname,
                '消费金额' => $d->money,
                '一级分销手机号' => $firstMobile,
                '一级分销金额' => $firstMoney,
                '二级分销手机号' => $secondMobile,
                '二级分销金额' => $secondMoney,
                '团队分销手机号' => $teamMobile,
                '团队分销金额' => $teamMoney,
                '购买时间' => $d->pay_time
            ];
        }
        
        return collect($orderData);
    }

    /**
     * 获取订单列表
     *
     * @param array $search
     *            搜索条件
     * @param array $field
     *            查询字段
     * @param number $limit
     *            每页显示条数
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function getOrdersList($search, $field = ['*'], $limit = 10, $isExport = 0)
    {
        $orders = Order::withTrashed()->with([
            'business' => function ($query) {
                $query->select('id', 'name');
            },
            'user' => function ($query) {
                $query->select('id', 'nickname');
            }
        ])
            ->where(function ($query) use ($search) {
            // 按提交订单时间搜索
            if (isset($search['start_created_at']) && $search['start_created_at']) {
                $query->where('created_at', '>=', $search['start_created_at']);
            }
            // 按提交订单时间搜索
            if (isset($search['end_created_at']) && $search['end_created_at']) {
                $query->where('created_at', '<=', $search['end_created_at']);
            }
            // 按订单号搜索
            if (isset($search['sn']) && $search['sn']) {
                $query->where('sn', $search['sn']);
            }
            // 按订单类型搜索
            if (isset($search['type']) && $search['type'] != 'all') {
                $query->where('type', $search['type']);
            }
            // 按订单类型搜索
            if (isset($search['status']) && $search['status'] != 'all') {
                $query->where('status', $search['status']);
            }
        })
            ->whereHas('user', function ($query) use ($search) {
            if (isset($search['nickname']) && $search['nickname']) {
                $query->where('nickname', $search['nickname']);
            }
        })
            ->orderBy('id', 'DESC');
        
        if ($isExport) {
            $orders = $orders->get($field);
            
            $orders->each(function ($item) {
                $item->product = [
                    'id' => $item->extend->copy['id'],
                    'name' => $item->extend->copy['name'],
                    'subtitle' => $item->extend->copy['subtitle']
                ];
            });
            
            return self::getExcelArrayData($orders);
        } else {
            $orders = $orders->paginate($limit, $field);
            
            $orders->each(function ($item) {
                $item->product = [
                    'id' => $item->extend->copy['id'],
                    'name' => $item->extend->copy['name'],
                    'subtitle' => $item->extend->copy['subtitle']
                ];
            });
            
            return self::returnCode('sys.success', $orders);
        }
    }

    /**
     * 获取EXCEL数据
     *
     * @param Order $orders
     * @return \Illuminate\Support\Collection
     */
    static protected function getExcelArrayData($orders)
    {
        if ($orders->isEmpty()) {
            return collect([]);
        }
        
        $orderData = [];
        
        foreach ($orders as $order) {
            $productName = isset($order->product->name) ? $order->product->name : '';
            $businessName = isset($order->business->name) ? $order->business->name : '';
            $userName = isset($order->user->nickname) ? $order->user->nickname : '';
            $userName = isset($order->user->nickname) ? $order->user->nickname : '';
            $userName = isset($order->user->nickname) ? $order->user->nickname : '';
            $areaName1 = isset($order->areaValue[0]) ? $order->areaValue[0]['name'] : '';
            $areaName2 = isset($order->areaValue[1]) ? $order->areaValue[1]['name'] : '';
            $areaName3 = isset($order->areaValue[2]) ? $order->areaValue[2]['name'] : '';
            $orderTypeName = '';
            switch ($order->type) {
                case Order::TYPE_LOCAL:
                    $orderTypeName = '吃喝玩乐go订单';
                    break;
                case Order::TYPE_PLACE:
                    $orderTypeName = '联盟商城订单';
                    break;
            }
            
            $orderStatusName = '';
            switch ($order->status) {
                case Order::STATUS_UNPAID:
                    $orderStatusName = '未支付';
                    break;
                case Order::STATUS_PAID:
                    $orderStatusName = '已支付';
                    break;
                case Order::STATUS_RESERVED:
                    $orderStatusName = '已预约';
                    break;
                case Order::STATUS_SHIPPED:
                    $orderStatusName = '已发货';
                    break;
                case Order::STATUS_COMPLETED:
                    $orderStatusName = '已完成';
                    break;
            }
            
            $orderData[] = [
                '订单号' => $order->sn,
                '产品名称' => $productName,
                '商家名称' => $businessName,
                '买家昵称' => $userName,
                '用户姓名' => $order->name,
                '联系电话' => $order->tel,
                '购买数量' => $order->quantity,
                '购买金额' => $order->money,
                '订单类型' => $orderTypeName,
                '订单状态' => $orderStatusName,
                '提交订单时间' => $order->created_at,
                '订单完成时间' => $order->complete_time,
                '备注' => $order->remark,
                '所在地区' => $areaName1 . $areaName2 . $areaName3,
                '详细地址' => $order->address,
                '快递公司' => $order->express_company,
                '快递单号' => $order->express_number
            ];
        }
        
        return collect($orderData);
    }
}