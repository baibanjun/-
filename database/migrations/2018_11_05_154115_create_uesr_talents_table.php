<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUesrTalentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uesr_talents', function (Blueprint $table) {
            
            $table->integer('uid',false,true)->default(0)->comment('用户id');
            $table->string('name',100)->comment('姓名');
            $table->char('mobile',11)->comment('手机号码');
            $table->tinyInteger('team',false,true)->default(0)->comment('达人角色是否开通团队 0:未开通,1:已开通');
            $table->tinyInteger('status',false,true)->default(1)->comment('达人角色状态 1:正常(通过),2:冻结');
            
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
        Schema::dropIfExists('uesr_talents');
    }
}
