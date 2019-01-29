<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Alter4OrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('wx_nonce_str',32)->nullable()->after('status')->comment('微信随机字符串');
            $table->char('wx_sign',32)->nullable()->after('status')->comment('微信签名');
            $table->char('wx_prepay_id',64)->nullable()->after('status')->comment('微信预支付交易会话标识');
            $table->dateTime('complete_time')->nullable()->after('verification_time')->comment('订单完成时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function ($table) {
            $table->dropColumn(['wx_nonce_str','wx_sign','wx_prepay_id','complete_time']);
        });
    }
}
