<?php

namespace App\Http\Controllers;

use App\UserRol;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests\RolStoreRequest;
use App\Http\Requests\RolUpdateRequest;
use App\Http\Requests\RolDestroyRequest;
use App\Http\Requests\RolPermissionAddRequest;
use App\Http\Requests\RolPermissionRemoveRequest;

class UserRolesController extends Controller
{
    public function store(RolStoreRequest $request)
    {
        if (!$role = UserRol::create([
            'name' => $request->name,
            'short_description' => $request->short_description,
        ])) {
            ResponseController::set_errors(true);
            ResponseController::set_messages("Error creando el rol");
            return ResponseController::response('BAD REQUEST');
        }

        ResponseController::set_messages("role creado");
        ResponseController::set_data(['id_role' => $role->id]);
        return ResponseController::response('CREATED');
    }

    public function get()
    {
        ResponseController::set_data(['roles' => UserRol::all()]);
        return ResponseController::response('OK');
    }

    public function update(RolUpdateRequest $request)
    {
        try {
            $role = UserRol::find($request->id_role);
            $role->name = $request->name;
            $role->short_description = $request->short_description;
            $role->save();
        } catch (\Exception $e) {
            ResponseController::set_errors(true);
            ResponseController::set_messages("error actualizando el rol");
            ResponseController::set_messages($e->getMessage());
            return ResponseController::response('BAD REQUEST');
        }

        ResponseController::set_messages("role actualizado");
        ResponseController::set_data(['id_role' => $role->id]);
        return ResponseController::response('OK');
    }

    public function destroy(RolDestroyRequest $request)
    {
        try {
            UserRol::destroy($request->id_role);
        } catch (\Exception $e) {
            ResponseController::set_errors(true);
            ResponseController::set_messages("error eliminado el role");
            ResponseController::set_messages($e->getMessage());
            return ResponseController::response('BAD REQUEST');
        }

        ResponseController::set_messages("role eliminado");
        return ResponseController::response('OK');
    }

    public function permissions($id_role)
    {
        $validator = Validator::make(['id_role' => $id_role], [
            'id_role' => 'required|integer|min:1|exists:user_roles,id',
        ]);

        if ($validator->fails()) {
            ResponseController::set_errors(true);
            ResponseController::set_messages($validator->errors()->toArray());
            return ResponseController::response('BAD REQUEST');
        }

        $roles[] = UserRol::find($id_role);

        $permissions = [];
        foreach ($roles as $index => $role) {
            foreach ($role->permissions as $permission) {
                $permissions[$permission->id] = $permission->name;
            }
        }

        ResponseController::set_data(['permissions' => $permissions]);

        return ResponseController::response('OK');
    }

    public function add_permission(RolPermissionAddRequest $request)
    {
        $role = UserRol::find($request->id_role);

        try {
            $role->permissions()->attach($request->id_permission);
        } catch (\Exception $e) {
            ResponseController::set_errors(true);
            ResponseController::set_messages("error asignando el permiso");
            ResponseController::set_messages($e->getMessage());
            return ResponseController::response('BAD REQUEST');
        }

        ResponseController::set_messages("permiso asignado");
        return ResponseController::response('OK');
    }

    public function remove_permission(RolPermissionRemoveRequest $request)
    {
        $role = UserRol::find($request->id_role);

        try {
            $role->permissions()->detach($request->id_permission);
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
