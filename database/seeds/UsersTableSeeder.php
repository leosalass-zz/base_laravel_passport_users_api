<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seeds[] = [
            'id_state' => '1',
            'name' => 'full name',
            'display_name' => 'system administrator',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
            'max_roles' => '0',
        ];

        DB::table('users')->insert($seeds);
    }
}
