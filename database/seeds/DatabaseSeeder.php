<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
      $this->call(UserPermissionsTableSeeder::class);
      $this->call(UserRolesTableSeeder::class);
      $this->call(UserRoleHasUserPermissionsTableSeeder::class);
      $this->call(UserStatesTableSeeder::class);
      $this->call(UsersTableSeeder::class);
      $this->call(UserHasRolesTableSeeder::class);
    }
}
