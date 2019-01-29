<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('subtitle')->after('name')->comment('副标题');
            
            $table->dropColumn(['sale_price', 'price', 'quantity_sold','onhand']);
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function ($table) {
            $table->dropColumn('subtitle');
            
            $table->decimal('sale_price',18,2)->comment('销售价');
            $table->decimal('price',18,2)->comment('门市价');
            $table->integer('quantity_sold',false,true)->default(0)->comment('已出售数量');
            $table->integer('onhand',false,true)->comment('库存');
        });
    }
}
