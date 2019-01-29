<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderExtend extends Model
{
    protected $primaryKey = 'order_id';
    
    protected $fillable = ['order_id','primary_distribution_uid','secondary_distribution_uid','copy','captain_uid'];
    
    protected $casts = ['copy'=>'json'];
    
    public function f_talent(){
        return $this->belongsTo('App\Models\UserTalent','primary_distribution_uid','uid');
    }
    
    public function s_talent(){
        return $this->belongsTo('App\Models\UserTalent','secondary_distribution_uid','uid');
    }
    
    public function order(){
        return $this->belongsTo('App\Models\Order','order_id');
    }
}
