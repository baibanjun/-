<?php
namespace App\Services\Interfaces\Lottery;

/**
 * 抽奖
 *
 * @author lilin
 *         wx(tel):13408099056
 *         qq:182436607
 *        
 */
interface LotteryInterface
{
    /**
     * 领取优惠卷
     * @var integer
     */
    const CALLBACK_TYPE_1 = 1;
    
    /**
     * 分享增加次数
     * @var integer
     */
    const CALLBACK_TYPE_2 = 2;
    
    /**
     * 分享优惠卷
     *
     * @param int $callbackType 回调类型 1:领取优惠卷 2:分享增加次数
     * @param int $id type 为1时表示优惠卷id 为2时表示活动的id
     */
    public function shareCallback($callbackType, $id);
    
    /**
     * 优惠卷详情
     *
     * @param int $couponId
     */
    public function getCouponDetails($couponId);
    
    /**
     * 转赠优惠卷
     *
     * @param int $couponId
     */
    public function giveCoupon($couponId);
    
    /**
     * 获取转赠优惠卷
     *
     * @param int $couponId
     */
    public function getGiveCoupon($couponId, $fromUid);
    
    /**
     * 获取用户对应类型的优惠卷
     *
     * @param int $type
     */
    public function getUserCoupons($type);
    
    /**
     * 开始抽奖
     *
     * @param int $id 抽奖活动的ID
     */
    public function startLottery($id);
    
    /**
     * 获取用户今天的抽奖次数
     *
     * @param int   $lotteryId  抽奖活动id
     * @param array $fields     显示的字段
     */
    public function getUserTodayPrizeNumber($lotteryId, $fields);
    
    /**
     * 用户中奖
     *
     * @param int       $lottery_draws_id   活动id
     * @param int       $prize_id           奖品id
     * @param int       $draw_type          奖品类别
     * @param string    $start_date         开始使用时间
     * @param string    $end_date           结束使用时间
     */
    public function createLottery($lottery_draws_id, $prize_id, $draw_type, $start_date, $end_date);
    
    /**
     * 用户增加(减少)对应抽奖次数
     *
     * @param int $lotteryId    活动id
     * @param int $number       次数
     */
    public function incrementTodayPrizeNumber($lotteryId, $number);
    
    /**
     * 通过id获取奖品详情
     *
     * @param int   $id     奖品id
     * @param array $fields 显示的字段
     */
    public function getPrizeDetailsById($id, $fields);
    
    /**
     * 获取抽奖活动对应的奖品列表
     *
     * @param int $id
     */
    public function getPrizeList($id);
    
    /**
     * 获取中奖用户
     *
     * @param int $id 抽奖活动的ID
     */
    public function getLotteryUser($id);
    
    /**
     * 获取详情
     *
     * @param int $id 抽奖活动的ID
     */
    public function getLotteryDetails($id);
}
