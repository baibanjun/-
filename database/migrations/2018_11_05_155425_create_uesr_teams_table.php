<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUesrTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uesr_teams', function (Blueprint $table) {
            
            $table->integer('uid',false,true)->default(0)->comment('用户id');
            $table->integer('number_of_team_users',false,true)->default(0)->comment('团队人数');
            $table->integer('number_of_satisfied_popler',false,true)->default(0)->comment('满足条件的用户数,同已卖出产品人数');
            $table->tinyInteger('status',false,true)->default(0)->comment('团队状态 0:正常,1:冻结');
            
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
        Schema::dropIfExists('uesr_teams');
    }
}
