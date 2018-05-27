<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserHasRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seeds[] = [
            'id_user' => '1',
            'id_role' => '1',
        ];
        DB::table('user_has_roles')->insert($seeds);
    }
}
