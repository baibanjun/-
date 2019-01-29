<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUesrOrderStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uesr_order_statistics', function (Blueprint $table) {

            $table->integer('uid',false,true)->default(0)->comment('用户id');
            $table->integer('completed_order_quantity',false,true)->default(0)->comment('已完成订单数量');
            $table->integer('subscribe_order_quantity',false,true)->default(0)->comment('已预约订单数量');
            $table->integer('paid_order_quantity',false,true)->default(0)->comment('已支付订单数量');
            $table->integer('unpaid_order_quantity',false,true)->default(0)->comment('未支付订单数量');
            
            $table->primary('uid');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uesr_order_statistics');
    }
}
