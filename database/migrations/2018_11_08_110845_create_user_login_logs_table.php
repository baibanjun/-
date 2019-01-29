<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLoginLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_login_logs', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('uid',false,true)->comment('用户ID');
            $table->string('token')->comment('TOKEN');
            $table->tinyInteger('platform',false,true)->default(0)->comment('平台 1:vx');
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
        Schema::dropIfExists('user_login_logs');
    }
}
