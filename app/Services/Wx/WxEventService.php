<?php
namespace App\Services\Wx;

use App\Services\Interfaces\Wx\WxEventInterface;
use App\Services\UserService;
use App\Services\WeixinService;
use App\Models\User;
use App\Services\AccountService;
use App\Models\UserAccountRecord;
use App\Models\AdminSet;
use App\Models\UserAccount;

/**
 * 微信推送事件
 *
 * @author lilin
 *         wx(tel):13408099056
 *         qq:182436607
 *        
 */
class WxEventService extends WxToolService implements WxEventInterface
{

    public function msgShortvideo()
    {}

    /**
     *
     * {@inheritdoc}
     * @see \App\Services\Interfaces\Wx\WxEventInterface::eventClick()
     */
    public function eventClick(array $eventData)
    {
        self::setLog('click,接收到的', 0, $eventData);
        
        switch ($eventData['EventKey']) {
            case 'click1':
                return $this->repImg($eventData['FromUserName'], '5Jl8cQIWytf04-2yH4FYE92vpgH0AnXkDJdMbTYEtGA');
                break;
            case 'click2':
                return $this->repText($eventData['FromUserName'], "客服小白电话：\n18848465761\n工作时间8：00—22：00");
                break;
            case 'click3':
                return $this->repText($eventData['FromUserName'], "商务合作请联系微信：\n17345846137");
                break;
        }
    }

    public function msgLocation()
    {}

    public function eventLocation()
    {}

    public function msgVideo()
    {}

    /**
     *
     * {@inheritdoc}
     * @see \App\Services\Interfaces\Wx\WxEventInterface::msgText()
     */
    public function msgText(array $eventData)
    {
        switch ($eventData['Content']) {
            case '三带一':
                $result = $this->repImg($eventData['FromUserName'], env('MEDIA_ID_1'));
                break;
            default:
                $result = $this->repText($eventData['FromUserName'], '已收到');
        }
        
        return $result;
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Services\Interfaces\Wx\WxEventInterface::eventSubscribe()
     */
    public function eventSubscribe(array $eventData)
    {
        // 关注
        if ($eventData['Event'] == 'subscribe') {
            // 获取用户
            $weixin = new WeixinService();
            $userInfo = $weixin->getUserInfo($eventData['FromUserName']);
            $userInfo['is_subscribe'] = User::IS_SUBSCRIBE_1;
            User::updateOrCreate([
                'openid' => $eventData['FromUserName']
            ], $userInfo);
            
            $repText = config('console.wx_subscribe_tip');
            return $this->repText($eventData['FromUserName'], $repText);
        }
        
        // 取消关注
        if ($eventData['Event'] == 'unsubscribe') {
            $user = User::where('openid', $eventData['FromUserName'])->first();
            $user->is_subscribe = User::IS_SUBSCRIBE_0;
            $user->save();
        }
    }

    public function msgVoice()
    {}

    public function msgLink()
    {}

    /**
     *
     * {@inheritdoc}
     * @see \App\Services\Interfaces\Wx\WxEventInterface::eventQrCode()
     */
    public function eventQrCode(array $eventData)
    {
        $repText = config('console.wx_subscribe_tip');
        $addMoney = false;
        $eventData['EventKey'] = isset($eventData['EventKey']) && $eventData['EventKey'] ? $eventData['EventKey'] : 'str';
        $eventKey = str_replace('qrscene_', '', $eventData['EventKey']);
        
        // 解析内容
        $data = json_decode($eventKey);
        self::setLog('扫描带参数二维码事件,解析出来的参数', 0, $data);
        
        // 获取用户
        $weixin = new WeixinService();
        $userInfo = $weixin->getUserInfo($eventData['FromUserName']);
        self::setLog('微信获取用户详情', 0, $userInfo);
        
        // 查找有没有此用户数据,没有创建用户,建立关系
        $user = UserService::getUserInfoByConditions([
            [
                'openid' => $eventData['FromUserName']
            ]
        ]);
        
        // 只要进入些方法,用户100%是关注了公众号的
        User::where('openid', $eventData['FromUserName'])->update([
            'is_subscribe' => User::IS_SUBSCRIBE_1
        ]);
        
        if (($user && isset($user['data']->inviter) && $user['data']->inviter) || ! isset($data->id)) {
            self::setLog('关注或者扫码,(用户为真&&邀请人为真) || data->id 不存在', 0, [
                'inviter' => $user['data']->inviter,
                'data' => $data
            ]);
            return $user['data']->is_subscribe ? '' : $this->repText($eventData['FromUserName'], $repText);
        }
        
        if (! isset($user['data']->id) || $user['data']->id != $data->id) {
            $userInfo['inviter'] = $data->id;
            $addMoney = true;
        }
        
        $addUser = User::updateOrCreate([
            'openid' => $eventData['FromUserName']
        ], $userInfo);
        
        // 邀请加钱
        if ($addMoney && $addUser) {
            $uid = $data->id;
            $money = AdminSet::where('type_name', 'attention')->first([
                'value'
            ])->value;
            
            // 如果用户没有帐户,则开通一个
            $exist = UserAccount::where('uid', $uid)->exists();
            if (! $exist) {
                UserAccount::create([
                    'uid' => $uid
                ]);
            }
            
            $objectType = UserAccountRecord::OBJECT_TYPE_3;
            $balance = UserAccount::where('uid', $uid)->first([
                'balance'
            ])->balance;
            $objectId = $addUser->id;
            
            self::setLog('邀请用户加钱', 0, [
                '$uid' => $uid,
                'money' => $money['money'],
                'objectType' => $objectType,
                'balance' => $balance,
                'objectId' => $objectId
            ]);
            
            AccountService::addMoney($uid, $money['money'], $objectType, $balance, $objectId);
        }
        
        return $user['data']->is_subscribe ? '' : $this->repText($eventData['FromUserName'], $repText);
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Services\Interfaces\Wx\WxEventInterface::repText()
     */
    public function repText($toUsername, $content)
    {
        $data = [
            'ToUserName' => $toUsername,
            'FromUserName' => config('console.wx_auth_account'),
            'CreateTime' => time(),
            'MsgType' => 'text',
            'Content' => $content
        ];
        
        return self::arrayToXml($data);
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Services\Interfaces\Wx\WxEventInterface::repImg()
     */
    public function repImg($toUsername, $mediaId)
    {
        self::setLog('回复图片');
        $data = [
            'ToUserName' => $toUsername,
            'FromUserName' => config('console.wx_auth_account'),
            'CreateTime' => time(),
            'MsgType' => 'image',
            'MediaId' => $mediaId
        ];
        
        return sprintf('<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[image]]></MsgType><Image><MediaId><![CDATA[%s]]></MediaId></Image></xml>', $toUsername, $data['FromUserName'], $data['CreateTime'], $data['MediaId']);
    }
}

