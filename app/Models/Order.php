<?php
namespace App\Models;

use App\Services\AreaService;

class Order extends BaseMysql
{

    /**
     * 吃喝玩乐go
     *
     * @var integer
     */
    const TYPE_LOCAL = 1;
    
    const TYPE_LOCAL_TIP = '吃喝玩乐go';

    /**
     * 周边
     *
     * @var integer
     */
    const TYPE_CIRCUM = 2;
    
    const TYPE_CIRCUM_TIP = '周边';

    /**
     * 联盟商城
     *
     * @var integer
     */
    const TYPE_PLACE = 3;
    
    const TYPE_PLACE_TIP = '联盟商城';

    /**
     * 未支付
     *
     * @var integer
     */
    const STATUS_UNPAID = 0;

    /**
     * 已支付
     *
     * @var integer
     */
    const STATUS_PAID = 1;

    /**
     * 已预约
     *
     * @var integer
     */
    const STATUS_RESERVED = 2;

    /**
     * 已发货(仅地方订单使用)
     *
     * @var integer
     */
    const STATUS_SHIPPED = 3;

    /**
     * 已完成(仅地方订单确认收货,或者7天自动收货)
     *
     * @var integer
     */
    const STATUS_COMPLETED = 4;

    /**
     * 收货角色:系统
     *
     * @var integer
     */
    const RECEIVED_ROLE_SYS = 1;

    /**
     * 收货角色:用户
     *
     * @var integer
     */
    const RECEIVED_ROLE_USER = 2;
    
    /**
     * 不发送预约短信
     * @var integer
     */
    const SEND_SMS_0 = 0;
    
    /**
     * 发送预约短信
     * @var integer
     */
    const SEND_SMS_1 = 1;

    protected $fillable = [
        'code',
        'sn',
        'uid',
        'type',
        'business_id',
        'product_id',
        'standard_id',
        'quantity',
        'money',
        'name',
        'tel',
        'area_code',
        'address',
        'remark',
        'express_company',
        'express_number',
        'pay_time',
        'complete_time',
        'verification_time',
        'status',
        'wx_prepay_id',
        'wx_sign',
        'wx_nonce_str'
    ];

    protected $appends = [
        'area_value',
        'expiration'
    ];
    
    public function getExpirationAttribute()
    {
        $result = 0;
        
        if (isset($this->attributes['created_at']) && isset($this->attributes['status'])){
            //过期时间
            $expirationTime = config('console.wx_pay_expiration_time');
            $orderExpirationTime = strtotime($this->created_at) + $expirationTime;
            $result = $orderExpirationTime < time() ? 1 : 0;
        }
        
        return $result;
    }

    /**
     * 关联产品
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }

    /**
     * 关联订单扩展
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function extend()
    {
        return $this->hasOne('App\Models\OrderExtend', 'order_id', 'id');
    }
    
    /**
     * 关联规格
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function standard()
    {
        return $this->hasOne('App\Models\ProductStandard', 'id', 'standard_id');
    }

    /**
     * 关联用户
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'uid', 'id');
    }

    public function getAreaValueAttribute()
    {
        return isset($this->attributes['area_code']) ? AreaService::getAreaByCode($this->attributes['area_code']) : [];
    }

    /**
     * 关联商家
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function business()
    {
        return $this->belongsTo('App\Models\Business', 'business_id');
    }

    /**
     * 关联账户记录
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accountRecord()
    {
        return $this->hasMany('App\Models\UserAccountRecord', 'object_id')->whereIn('object_type', [
            UserAccountRecord::OBJECT_TYPE_1,
            UserAccountRecord::OBJECT_TYPE_2,
            UserAccountRecord::OBJECT_TYPE_6
        ]);
    }
}
