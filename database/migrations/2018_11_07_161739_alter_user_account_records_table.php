<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserAccountRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_account_records', function (Blueprint $table) {
            $table->string('key')->after('recommended_user')->nullable()->comment('防重复操作');
            
            $table->decimal('now_money', 18 ,2)->after('money')->default('0.00')->comment('当前还有多少金额');
            
            $table->unique('key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_account_records', function ($table) {
            $table->dropColumn(['key','now_money']);
        });
    }
}
