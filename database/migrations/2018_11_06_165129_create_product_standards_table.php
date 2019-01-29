<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductStandardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_standards', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('pid', false, true)->comment('产品ID');
            $table->string('name')->comment('规格名');
            $table->decimal('sale_price',18,2)->comment('销售价');
            $table->decimal('price',18,2)->comment('门市价');
            $table->integer('quantity_sold',false,true)->default(0)->comment('已出售数量');
            $table->integer('onhand',false,true)->comment('库存');
            
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
        Schema::dropIfExists('product_standards');
    }
}
