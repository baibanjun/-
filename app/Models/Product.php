<?php

namespace App\Models;

class Product extends BaseMysql
{
    protected $guarded = [];
    
    /**
     * 否
     * @var integer
     */
    const IS_NO = 0;
    
    /**
     * 是
     * @var integer
     */
    const IS_YES = 1;
    
    /**
     * 本地
     * @var integer
     */
    const TYPE_LOCAL = 1;
    
    /**
     * 周边
     * @var integer
     */
    const TYPE_CIRCUM = 2;
    
    /**
     * 地方
     * @var integer
     */
    const TYPE_PLACE = 3;
    
    /**
     * 已上架
     * @var integer
     */
    const STATUS_ITEM_UPSHELF = 1;
    
    /**
     * 已下架
     * @var integer
     */
    const STATUS_SOLD_OUT = 2;
    
    /**
     * 已隐藏
     * @var integer
     */
    const STATUS_HIDE = 3;
    
    /**
     * 不开启倒计时
     * @var integer
     */
    const IS_COUNTDOWN_0 = 0;
    
    /**
     * 开启倒计时
     * @var integer
     */
    const IS_COUNTDOWN_1 = 1;
    
    
    protected $casts = ['poster'=>'json','pics'=>'json'];
    
    /**
     * 关联商家
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function business()
    {
        return $this->hasOne('App\Models\Business', 'id', 'business_id');
    }
    
    /**
     * 关联规格
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function standards()
    {
        return $this->hasMany('App\Models\ProductStandard', 'pid', 'id');
    }
    
    /**
     * 关联一级分销
     *
     * @return 
     */
    public function primaryDistribution()
    {
        return $this->hasOne('App\Models\Distribution','id','primary_distribution_id')->where('class_type', Distribution::CLASS_TYPE_PRIMARY_DISTRIBUTION);
    }
    
    /**
     * 关联地区
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sysArea()
    {
        return $this->belongsTo('App\Models\SysArea', 'city_code', 'code');
    }
    
    /**
     * 关联一级分销
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function primarydDistribution()
    {
        return $this->belongsTo('App\Models\Distribution', 'primary_distribution_id');
    }
    
    /**
     * 关联二级分销
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function secondaryDistribution()
    {
        return $this->belongsTo('App\Models\Distribution', 'secondary_distribution_id');
    }
    
    /**
     * 关联团队分销
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function teamDistribution()
    {
        return $this->belongsTo('App\Models\Distribution', 'team_distribution_id');
    }
    
}
