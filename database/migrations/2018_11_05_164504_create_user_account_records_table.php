<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAccountRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_account_records', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('uid',false,true)->default(0)->comment('用户id');
            $table->decimal('money',18,2)->comment('金额: 佣金或者提现金额');
            $table->tinyInteger('object_type')->comment('对象类型 1:分销收入 2:团队收入 3:推荐用户收入 4:提现扣款');
            $table->integer('object_id')->comment('对象ID:订单id,用户id,提现id');
            $table->json('primary_distribution')->nullable()->comment('一级分销收入配制内容 类型,类型对应的比例或者金额');
            $table->json('secondary_distribution')->nullable()->comment('二级分销收入配制内容 类型,类型对应的比例或者金额');
            $table->json('recommended_user')->nullable()->comment('推荐用户收入配制内容');
            
            $table->index('uid');
            $table->index('object_type');
            
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
        Schema::dropIfExists('user_account_records');
    }
}
