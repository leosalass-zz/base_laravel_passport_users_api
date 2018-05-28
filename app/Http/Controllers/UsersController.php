<?php

namespace App\Http\Controllers;

use App\User;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests\UsersCreateRequest;
use App\Http\Requests\UsersUpdateRequest;
use App\Http\Requests\UsersDestroyRequest;
use App\Http\Requests\UserRolesAddRequest;
use App\Http\Requests\UserRolesRemoveRequest;
use App\Http\Requests\UserPermissionAddRequest;
use App\Http\Requests\UserPermissionRemoveRequest;


class UsersController extends Controller
{
    public function store(UsersCreateRequest $request)
    {
        if (!$user = User::create([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ])) {
            ResponseController::set_errors(true);
            ResponseController::set_messages("Error creando el usuario");
            return ResponseController::response('BAD REQUEST');
        }

        ResponseController::set_messages("usuario creado");
        ResponseController::set_data(['user_id' => $user->id]);
        return ResponseController::response('CREATED');
    }

    public function get($id_user)
    {
        $validator = Validator::make(['id_user' => $id_user], [
            'id_user' => 'required|integer|min:1|exists:users,id',
        ]);

        if ($validator->fails()) {
            ResponseController::set_errors(true);
            ResponseController::set_messages($validator->errors()->toArray());
            return ResponseController::response('BAD REQUEST');
        }

        ResponseController::set_data(['user' => User::find($id_user)]);
        return ResponseController::response('OK');
    }

    public function get_all()
    {
        ResponseController::set_data(['users' => User::all()]);
        return ResponseController::response('OK');
    }

    public function update(UsersUpdateRequest $request)
    {
        $user = User::find($request->id_user);
        if (isset($request->password)) {
            $user->password = $request->password;
        }
        if (isset($request->email)) {
            $user->email = $request->email;
        }
        if (isset($request->name)) {
            $user->name = $request->name;
        }
        if (isset($request->state)) {
            $user->id_state = $request->state;
        }

        try {
            $user->save();
        } catch (\Exception $e) {
            ResponseController::set_errors(true);
            ResponseController::set_messages("error creando el usuario");
            ResponseController::set_messages($e->getMessage());
            return ResponseController::response('BAD REQUEST');
        }

        ResponseController::set_messages("usuario actualizado");
        return ResponseController::response('OK');

    }

    public function destroy(UsersDestroyRequest $request)
    {
        try {
            User::destroy($request->id_user);
        }catch (\Exception $e){
            ResponseController::set_errors(true);
            ResponseController::set_messages("error eliminado el usuario");
            ResponseController::set_messages($e->getMessage());
            return ResponseController::response('BAD REQUEST');
        }

        ResponseController::set_messages("usuario eliminado");
        return ResponseController::response('OK');
    }

    public function roles($id_user)
    {
        $validator = Validator::make(['id_user' => $id_user], [
            'id_user' => 'required|integer|min:1|exists:users,id',
        ]);

        if ($validator->fails()) {
            ResponseController::set_errors(true);
            ResponseController::set_messages($validator->errors()->toArray());
            return ResponseController::response('BAD REQUEST');
        }

        $user = User::find($id_user);
        ResponseController::set_data(['roles' => $user->roles]);

        return ResponseController::response('CREATED');
    }

    public function add_rol(UserRolesAddRequest $request)
    {
        $user = User::find($request->id_user);

        if(!$user){
            ResponseController::set_errors(true);
            ResponseController::set_messages("usuario no valido");
            return ResponseController::response('BAD REQUEST');
        }

        try {
            $user->roles()->attach($request->id_role);
        } catch (\Exception $e) {
            ResponseController::set_errors(true);
            ResponseController::set_messages("error asignando el rol");
            ResponseController::set_messages($e->getMessage());
            return ResponseController::response('BAD REQUEST');
        }

        ResponseController::set_messages("rol asignado");
        return ResponseController::response('CREATED');
    }

    public function remove_rol(UserRolesRemoveRequest $request)
    {
        $user = User::find($request->id_user);

        try {
            $user->roles()->detach($request->id_role);
        } catch (\Exception $e) {
            ResponseController::set_errors(true);
            ResponseController::set_messages("error eliminando el rol");
            return ResponseController::response('BAD REQUEST');
        }

        ResponseController::set_messages("rol eliminado");
        return ResponseController::response('OK');
    }

    public function permissions($id_user)
    {
        $validator = Validator::make(['id_user' => $id_user], [
            'id_user' => 'required|integer|min:1|exists:users,id',
        ]);

        if ($validator->fails()) {
            ResponseController::set_errors(true);
            ResponseController::set_messages($validator->errors()->toArray());
            return ResponseController::response('BAD REQUEST');
        }

        $user = User::find($id_user);

        $permissions = [];
        foreach ($user->roles as $index => $rol) {
            foreach ($rol->permissions as $permission) {
                $permissions[$permission->id] = $permission->name;
            }
        }

        foreach ($user->permissions as $permission) {
            $action = $permission->pivot->action;
            $name = $permission->name;

            $array_key = array_search($name, $permissions);
            switch ($action) {
                case 'remove':
                    if (in_array($name, $permissions)) {
                        unset($permissions[$array_key]);
                    }
                    break;

                case 'add':
                    if (!in_array($name, $permissions)) {
                        $permissions[$permission->id] = $name;
                    }
                    break;
            }
        }

        ResponseController::set_data(['permissions' => $permissions]);

        return ResponseController::response('CREATED');
    }

    public function add_permission(UserPermissionAddRequest $request)
    {
        $user = User::find($request->id_user);
        $action_name = ($request->action == 'add') ? 'asignado' : 'bloqueado';

        try {
            $user->permissions()->attach($request->id_permission, ['action' => $request->action]);
        } catch (\Exception $e) {
            ResponseController::set_errors(true);
            ResponseController::set_messages("error, permino no $action_name");
            ResponseController::set_messages($e->getMessage());
            return ResponseController::response('BAD REQUEST');
        }

        ResponseController::set_messages("permiso $action_name");
        return ResponseController::response('CREATED');
    }

    public function remove_permission(UserPermissionRemoveRequest $request)
    {
        $user = User::find($request->id_user);

        try {
            $user->permissions()->detach($request->id_permission);
        } catch (\Exception $e) {
            ResponseController::set_errors(true);
            ResponseController::set_messages("error eliminando el permiso");
            ResponseController::set_messages($e->getMessage());
            return ResponseController::response('BAD REQUEST');
        }

        ResponseController::set_messages("permiso eliminado");
        return ResponseController::response('OK');
    }
}
