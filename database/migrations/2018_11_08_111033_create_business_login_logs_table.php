<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessLoginLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_login_logs', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('uid',false,true)->comment('商家用户ID');
            $table->string('token')->comment('TOKEN');
            $table->tinyInteger('platform',false,true)->default(0)->comment('平台 1:默认');
            $table->ipAddress('login_ip')->comment('登录IP');
            
            $table->index('uid');
            
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
        Schema::dropIfExists('business_login_logs');
    }
}
