<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_users')->insert([
            'mobile' => '19999999999',
            'realname' => '默认管理员',
            'password' => '6EB67141E27FCFF6F423A22F14A98809', //q123456
            'salt' => '595cdc2191aea',
        ]);
    }
}
