<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCity extends Model
{
    protected $casts = ['city_code'=>'string'];
}
