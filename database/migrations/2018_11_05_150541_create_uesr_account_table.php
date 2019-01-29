<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUesrAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uesr_accounts', function (Blueprint $table) {

            $table->integer('uid',false,true)->default(0)->comment('用户id');
            $table->decimal('primary_distribution_money',18,2)->default(0.00)->comment('一级分销获得的总金额');
            $table->decimal('secondary_distribution_money',18,2)->default(0.00)->comment('二级分销获得的总金额');
            $table->decimal('team_distribution_money',18,2)->default(0.00)->comment('团队分销获得的总金额');
            $table->decimal('withdraw_money',18,2)->default(0.00)->comment('已经提现总金额');
            $table->decimal('balance',18,2)->default(0.00)->comment('账户余额');
            
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
        Schema::dropIfExists('uesr_accounts');
    }
}
