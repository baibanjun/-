<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserOrderStatistic extends Model
{
    protected $primaryKey = 'uid';
    
    protected $fillable = ['uid','completed_order_quantity','subscribe_order_quantity','shipped_order_quantity','paid_order_quantity','unpaid_order_quantity'];
}
