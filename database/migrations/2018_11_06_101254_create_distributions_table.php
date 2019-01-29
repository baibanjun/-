<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistributionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distributions', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('pid',false,true)->comment('产品id');
            $table->tinyInteger('class_type',false,true)->comment('分类类别 1:一级分销 2:二级分销 3:团队分销');
            $table->tinyInteger('type',false,true)->comment('分成方式 1:按比例 2:按固定金额');
            $table->string('value',20)->comment('比例或者固定金额');
            
            $table->index('pid');
            
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
        Schema::dropIfExists('distributions');
    }
}
