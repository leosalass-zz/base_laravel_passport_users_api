<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seed = [];
        /*
         * administrator permissions
         */
        $seed[] = [
            'name' => 'access.all',
            'short_description' => 'grant full interface access',
        ];
        $seed[] = [
            'name' => 'crud.all',
            'short_description' => 'grant access to all crud operations',
        ];
        /*
         * access permissions
         */
        $seed[] = [
            'name' => 'access.backend',
            'short_description' => 'grant backend interface access',
        ];
        $seed[] = [
            'name' => 'access.users',
            'short_description' => 'grant backend users interface access',
        ];
        $seed[] = [
            'name' => 'access.roles',
            'short_description' => 'grant backend roles interface access',
        ];
        $seed[] = [
            'name' => 'access.permissions',
            'short_description' => 'grant backend permissions interface access',
        ];
        /*
         * crud permissions
         */
        $seed[] = [
            'name' => 'user_roles.create',
            'short_description' => 'grant create access to user_roles',
        ];
        $seed[] = [
            'name' => 'user_roles.read',
            'short_description' => 'grant read access to user_roles',
        ];
        $seed[] = [
            'name' => 'user_roles.update',
            'short_description' => 'grant update access to user_roles',
        ];
        $seed[] = [
            'name' => 'user_roles.delete',
            'short_description' => 'grant delete access to user_roles',
        ];
        $seed[] = [
            'name' => 'user_has_user_roles.create',
            'short_description' => 'grant create access to user_has_user_roles',
        ];
        $seed[] = [
            'name' => 'user_has_user_roles.read',
            'short_description' => 'grant read access to user_has_user_roles',
        ];
        $seed[] = [
            'name' => 'user_has_user_roles.update',
            'short_description' => 'grant update access to user_has_user_roles',
        ];
        $seed[] = [
            'name' => 'user_has_user_roles.delete',
            'short_description' => 'grant delete access to user_has_user_roles',
        ];
        $seed[] = [
            'name' => 'user_permissions.create',
            'short_description' => 'grant create access to user_permissions',
        ];
        $seed[] = [
            'name' => 'user_permissions.read',
            'short_description' => 'grant read access to user_permissions',
        ];
        $seed[] = [
            'name' => 'user_permissions.update',
            'short_description' => 'grant update access to user_permissions',
        ];
        $seed[] = [
            'name' => 'user_permissions.delete',
            'short_description' => 'grant delete access to user_permissions',
        ];
        $seed[] = [
            'name' => 'user_has_user_permissions.create',
            'short_description' => 'grant create access to user_has_user_permissions',
        ];
        $seed[] = [
            'name' => 'user_has_user_permissions.read',
            'short_description' => 'grant read access to user_has_user_permissions',
        ];
        $seed[] = [
            'name' => 'user_has_user_permissions.update',
            'short_description' => 'grant update access to user_has_user_permissions',
        ];
        $seed[] = [
            'name' => 'user_has_user_permissions.delete',
            'short_description' => 'grant delete access to user_has_user_permissions',
        ];
        $seed[] = [
            'name' => 'user_roles_has_user_permissions.create',
            'short_description' => 'grant create access to user_roles_has_user_permissions',
        ];
        $seed[] = [
            'name' => 'user_roles_has_user_permissions.read',
            'short_description' => 'grant read access to user_roles_has_user_permissions',
        ];
        $seed[] = [
            'name' => 'user_roles_has_user_permissions.update',
            'short_description' => 'grant update access to user_roles_has_user_permissions',
        ];
        $seed[] = [
            'name' => 'user_roles_has_user_permissions.delete',
            'short_description' => 'grant delete access to user_roles_has_user_permissions',
        ];
        $seed[] = [
            'name' => 'users.create',
            'short_description' => 'grant access to users creation',
        ];
        $seed[] = [
            'name' => 'users.read',
            'short_description' => 'grant read access to current user to the users',
        ];
        $seed[] = [
            'name' => 'users.read.mine',
            'short_description' => 'grant read access to current user to his users',
        ];
        $seed[] = [
            'name' => 'users.update',
            'short_description' => 'grant update access to current user to the users',
        ];
        $seed[] = [
            'name' => 'users.update.mine',
            'short_description' => 'grant update access to current user to his users',
        ];
        $seed[] = [
            'name' => 'users.delete',
            'short_description' => 'delete users',
        ];
        $seed[] = [
            'name' => 'users.delete.mine',
            'short_description' => 'delete users',
        ];
        $seed[] = [
            'name' => 'users.ban',
            'short_description' => 'ban user',
        ];
        DB::table('user_permissions')->insert($seed);
    }
}
