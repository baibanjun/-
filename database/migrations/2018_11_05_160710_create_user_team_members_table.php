<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTeamMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_team_members', function (Blueprint $table) {
            $table->increments('id')->comment('主键');
            $table->integer('captain_uid',false,true)->default(0)->comment('队长用户id');
            $table->integer('team_member_uid',false,true)->default(0)->comment('队员用户id');
            $table->decimal('amount_of_product_sold',18,2)->default(0.00)->comment('卖出产品的金额');
            $table->tinyInteger('status',false,true)->default(0)->comment('关系状态 0:正常,1:冻结');
            $table->timestamps();
            $table->index('captain_uid');
            $table->index('team_member_uid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_team_members');
    }
}
