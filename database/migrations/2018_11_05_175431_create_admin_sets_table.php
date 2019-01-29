<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_sets', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('name')->comment('功能名称');
            $table->string('type_name')->comment('类别名称');
            $table->json('value')->comment('值或者内容');
            
            $table->unique('type_name');
            
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
        Schema::dropIfExists('admin_sets');
    }
}
