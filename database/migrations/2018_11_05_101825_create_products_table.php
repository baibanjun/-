<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('business_id',false,true)->comment('商家ID');
            $table->string('name')->comment('产品名称');
            $table->tinyInteger('type',false,true)->comment('类型 1:本地,2:周边,3:地方');
            $table->tinyInteger('is_countdown',false,true)->default(0)->comment('是否倒计时 0:不开启 1:开启');
            $table->integer('time_limit',false,true)->default(0)->comment('剩余时间,自动下回期限');
            $table->string('city_code')->comment('城市');
            $table->tinyInteger('status',false,true)->default(1)->comment('状态 1:已上架,2:已下架,3:已隐藏');
            $table->tinyInteger('send_sms_or_not',false,true)->comment('是否发送短信 0:不发放 1:发放');
            $table->string('booking_information')->nullable()->comment('预约信息');
            $table->decimal('sale_price',18,2)->comment('销售价');
            $table->decimal('price',18,2)->comment('门市价');
            $table->integer('quantity_sold',false,true)->default(0)->comment('已出售数量');
            $table->integer('onhand',false,true)->comment('库存');
            $table->integer('primary_distribution_id',false,true)->comment('一级分销ID');
            $table->integer('secondary_distribution_id',false,true)->comment('二级分销ID');
            $table->integer('team_distribution_id',false,true)->comment('团队分销ID');
            $table->json('poster')->nullable()->comment('产品分享海报');
            $table->json('pics')->nullable()->comment('产品轮播图');
            $table->text('content')->comment('详细内容');
            
            $table->index('business_id');
            $table->index('type');
            $table->index('city_code');
            $table->index('status');
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
        Schema::dropIfExists('products');
    }
}
