<?php
namespace App\Services\Interfaces\Order;

/**
 * 分销,订单分成,团队分成
 *
 * @author lilin
 *         wx(tel):13408099056
 *         qq:182436607
 *        
 */
interface DistributionInterface
{
    /**
     * 分销加钱
     *
     * @param array $config $this->howMuchMoney()
     */
    public function distribution($config);
    
    /**
     * 通过订单获取一二级分销配制
     *
     * @param integer $orderId
     */
    public function getOrderDisConfig();
    
    /**
     * 通过订单获取一二级分销用户,如果不是达人对应返回0,如果达人状态冻结或者用户状态冻结也对应返回0,如果一级和二级都为同一个人,只认一级
     *
     * @param integer $orderId
     */
    public function getOrderDisUser();
    
    /**
     * 通过订单获取团队-队长用户,如果不是达人对应返回0,如果达人状态冻结或者用户状态冻结也对应返回0,如果队长没有满足开团条件,返回0
     *
     * @param integer $teamMemberUid
     * @param boolean $isMeet           假:不须要查询是否满足配制的条件,真:相反
     */
    public function getCaptain($teamMemberUid, $isMeet);
    
    /**
     * 计算用户能获得多少钱
     *  团队分销只认一给分销用户的队长
     *
     */
    public function howMuchMoney();
}
