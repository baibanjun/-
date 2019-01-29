<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCashesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_cashes', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('uid',false,true)->default(0)->comment('用户id');
            $table->decimal('money',18,2)->comment('提现金额');
            $table->decimal('balance',18,2)->comment('提现后余额');
            $table->tinyInteger('status',false,true)->default(1)->comment('状态 1:已扣款 2:已打款 3:已驳回');
            
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
        Schema::dropIfExists('user_cashes');
    }
}
