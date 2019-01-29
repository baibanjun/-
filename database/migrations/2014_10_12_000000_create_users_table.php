<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * @author lilin
 *
 * 用户表
 *
 */
class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            
            $table->increments('id');
            
            //微信字段
            $table->char('openid',50)->unique()->comment('公众号用户ID（唯一）');
            $table->string('nickname',100)->nullable()->comment('昵称');
            $table->tinyInteger('sex',false,true)->nullable()->comment('姓名:0:女 1:男');
            $table->string('language',20)->nullable()->comment('语言');
            $table->string('city',100)->nullable()->comment('市');
            $table->string('province',100)->nullable()->comment('省');
            $table->string('country',100)->nullable()->default(0)->comment('国家');
            $table->string('headimgurl',200)->nullable()->comment('头像');
            
            //内部字段
            $table->tinyInteger('role',false,true)->default(0)->comment('角色 0:普通用户,1:达人');
            $table->tinyInteger('status',false,true)->default(0)->comment('普通角色状态 0:正常,1:冻结');
            
            $table->index('openid');
            $table->index('role');
            $table->index('status');
            
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
        Schema::dropIfExists('users');
    }
}
