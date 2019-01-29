<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            
            $table->char('code',10)->after('id')->nullable()->comment('电子码');
            $table->integer('uid',false,true)->default(0)->comment('用户id');
            $table->integer('business_id',false,true)->comment('商家ID');
            $table->integer('product_id',false,true)->comment('产品id');
            $table->integer('quantity',false,true)->comment('数量');
            $table->decimal('money',18,2)->comment('购买金额');
            $table->char('name',50)->comment('姓名:购买的联系人');
            $table->char('tel',11)->comment('联系电话,必须手机号');
            $table->char('area_code',10)->nullable()->comment('所在地区');
            $table->string('address')->nullable()->comment('详细地址');
            $table->string('remark')->nullable()->comment('备注');
            $table->string('express_company')->nullable()->comment('快递公司名称');
            $table->string('express_number')->nullable()->comment('快递单号');
            $table->tinyInteger('received_role')->default(0)->comment('收货角色 0:未收货 1:系统 2:用户');
            $table->dateTime('pay_time')->nullable()->comment('支付时间');
            $table->dateTime('verification_time')->nullable()->comment('核销时间,验证电子码');
            $table->tinyInteger('status',false,true)->default(0)->comment('状态 0:未支付 1:已支付 2:已预约 3:已发货(仅地方订单使用) 4:已完成(仅地方订单确认收货,或者7天自动收货)');
            
            $table->index('code');
            $table->index('uid');
            $table->index('business_id');
            $table->index('deleted_at');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
