<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSetTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_sets')->insert([
            [
                'name' => '邀请关注公众号奖励',
                'type_name' => 'attention',
                'value' => '{"money":0.5}'
            ],[
                'name' => '提现提示',
                'type_name' => 'withdrawal_prompt',
                'value' => '{"content":"满足50元即可提现,一个工作日内到帐"}'
            ]
        ]);
    }
}
