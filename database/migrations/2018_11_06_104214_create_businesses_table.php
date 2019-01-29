<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('name')->comment('商家名称');
            $table->string('tel')->comment('联系电话');
            $table->string('address')->comment('商家地址');
            $table->string('lng')->comment('经度');
            $table->string('lat')->comment('纬度');
            $table->string('username')->comment('核销系统用户名');
            $table->char('mobile',11)->comment('手机号');
            $table->string('password',128)->comment('核销系统密码');
            $table->string('salt',20)->comment('密码盐值');
            
            $table->index(['username', 'password']);
            $table->index(['mobile', 'password']);
            
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
        Schema::dropIfExists('businesses');
    }
}
