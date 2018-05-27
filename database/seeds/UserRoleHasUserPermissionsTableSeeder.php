<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRoleHasUserPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seeds[] = [
            'id_role' => '1',
            'id_permission' => '1',
        ];
        $seeds[] = [
            'id_role' => '1',
            'id_permission' => '2',
        ];
        DB::table('user_role_has_user_permissions')->insert($seeds);
    }
}
