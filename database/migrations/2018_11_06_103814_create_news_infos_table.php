<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_infos', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('title')->comment('新闻标题');
            $table->tinyInteger('type',false,true)->comment('类别 1:攻略 2:2台 3:探店');
            $table->string('auther',100)->comment('作者');
            $table->integer('city_code',false,true)->comment('城市');
            $table->json('pics')->nullable()->comment('宣传图');
            $table->text('content')->comment('内容');
            
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
        Schema::dropIfExists('news_infos');
    }
}
