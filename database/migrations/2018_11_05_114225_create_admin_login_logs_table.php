<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminLoginLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_login_logs', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('uid',false,true)->comment('后台用户ID');
            $table->string('token')->comment('TOKEN');
            $table->tinyInteger('platform',false,true)->default(0)->comment('平台：1网站后台');
            $table->ipAddress('login_ip')->comment('登录IP');
            
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
        Schema::dropIfExists('admin_login_logs');
    }
}
