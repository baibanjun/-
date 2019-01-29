<?php
namespace App\Services;

/**
 * 这里是说明
 *
 * @author lilin
 *         wx(tel):13408099056
 *         qq:182436607
 *        
 */
class ChwlFactoty
{

    public static function Lottery()
    {
        return new LotteryService();
    }
}

