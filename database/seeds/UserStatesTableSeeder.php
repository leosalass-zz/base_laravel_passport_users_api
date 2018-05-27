<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserStatesTableSeeder extends Seeder
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
            'name' => 'inactive',
            'short_description' => 'registered, but not activated, this user needs to be activated by email confirmation',
        ];
        $seed[] = [
            'name' => 'active',
            'short_description' => 'email validated user',
        ];
        $seed[] = [
            'name' => 'banned',
            'short_description' => 'this is a banned user',
        ];

        DB::table('user_states')->insert($seed);
    }
}
