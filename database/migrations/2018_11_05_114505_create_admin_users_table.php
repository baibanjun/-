<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_users', function (Blueprint $table) {
            $table->increments('id');
            
            $table->char('mobile',11)->comment('手机号');
            $table->string('realname')->comment('真实姓名');
            $table->string('password',128)->comment('密码');
            $table->string('salt',20)->comment('密码盐值');
            $table->json('powers')->nullable()->comment('权限');
            $table->tinyInteger('err_num',false,true)->default(0)->comment('错误次数');
            $table->dateTime('err_time')->nullable()->comment('错误时间');
            
            $table->index(['mobile', 'password']);
            $table->index('deleted_at');
            
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
        Schema::dropIfExists('admin_users');
    }
}
