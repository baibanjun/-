<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderExtendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_extends', function (Blueprint $table) {
            
            $table->integer('order_id',false,true)->comment('订单id');
            $table->integer('primary_distribution_uid',false,true)->comment('一级分销用户id');
            $table->integer('secondary_distribution_uid',false,true)->comment('二级分销用户id');
            $table->json('copy')->nullable()->comment('订单快照');
            
            $table->timestamps();
            
            $table->primary('order_id');
            $table->index('primary_distribution_uid');
            $table->index('secondary_distribution_uid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_extends');
    }
}
