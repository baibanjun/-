<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('uesr_accounts', function (Blueprint $table) {
            $table->decimal('recommended_user_money', 18, 2)->after('team_distribution_money')->default(0.00)->comment('推荐用户获得的总金额');
        });
        
            Schema::rename('uesr_accounts', 'user_accounts');
            Schema::rename('uesr_order_statistics', 'user_order_statistics');
            Schema::rename('uesr_talents', 'user_talents');
            Schema::rename('uesr_team_members', 'user_team_members');
            Schema::rename('uesr_teams', 'user_teams');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('uesr_accounts', function (Blueprint $table) {
            $table->dropColumn('recommended_user_money');
        });
    }
}
