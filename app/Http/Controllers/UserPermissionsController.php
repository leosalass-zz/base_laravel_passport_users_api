<?php

namespace App\Http\Controllers;

use App\UserPermission;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests\PermissionStoreRequest;
use App\Http\Requests\PermissionUpdateRequest;
use App\Http\Requests\PermissionDestroyRequest;

class UserPermissionsController extends Controller
{
    public function store(PermissionStoreRequest $request)
    {
        if (!$permission = UserPermission::create([
            'name' => $request->name,
            'short_description' => $request->short_description,
        ])) {
            ResponseController::set_errors(true);
            ResponseController::set_messages("Error creando el permiso");
            return ResponseController::response('BAD REQUEST');
        }

        ResponseController::set_messages("permiso creado");
        ResponseController::set_data(['rol_id' => $permission->id]);
        return ResponseController::response('CREATED');
    }

    public function get()
    {
        ResponseController::set_data(['permisos' => UserPermission::all()]);
        return ResponseController::response('OK');
    }

    public function update(PermissionUpdateRequest $request)
    {
        try {
            $permission = UserPermission::find($request->id_permission);
            $permission->name = $request->name;
            $permission->short_description = $request->short_description;
            $permission->save();
        } catch (\Exception $e) {
            ResponseController::set_errors(true);
            ResponseController::set_messages("error actualizando el permiso");
            ResponseController::set_messages($e->getMessage());
            return ResponseController::response('BAD REQUEST');
        }

        ResponseController::set_messages("permiso actualizado");
        ResponseController::set_data(['permiso_id' => $permission->id]);
        return ResponseController::response('OK');
    }

    public function destroy(PermissionDestroyRequest $request)
    {
        try {
            UserPermission::destroy($request->id_permission);
        } catch (\Exception $e) {
            ResponseController::set_errors(true);
            ResponseController::set_messages("error eliminado el permiso");
            ResponseController::set_messages($e->getMessage());
            return ResponseController::response('BAD REQUEST');
        }

        ResponseController::set_messages("permiso eliminado");
        return ResponseController::response('OK');
    }
}
