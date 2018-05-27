<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seed = [];

        $seed[] = [
            'name' => 'administrator',
            'short_description' => 'This is a full permissions user',
        ];
        $seed[] = [
            'name' => 'client',
            'short_description' => 'this user has limited access from bussines owner',
        ];
        $seed[] = [
            'name' => 'seller',
            'short_description' => 'this is a bussiness seller, and he has limited access to his clients',
        ];
        $seed[] = [
            'name' => 'ceo',
            'short_description' => 'this is the bussiness CEO/OWNER, and he has limited access to his entired bussines, includes reports from all clients, sellers, etc..',
        ];
        $seed[] = [
            'name' => 'unregistered',
            'short_description' => 'this is a public user',
        ];

        DB::table('user_roles')->insert($seed);
    }
}
