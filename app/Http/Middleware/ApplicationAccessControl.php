<?php

namespace App\Http\Middleware;

use App\Http\Controllers\ResponseController;
use App\UserRol;
use Closure;
use Illuminate\Support\Facades\Auth;

class ApplicationAccessControl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next, ...$required_permissions)
    {
        $permissions = [];
        if (Auth::guest()) {
            $roles[] = UserRol::find('5');//unregistered predefined user_rol
        } else {
            $roles = Auth::user()->roles;
        }

        /*ResponseController::set_errors($roles);
        return ResponseController::response('UNAUTHORIZED');*/

        foreach ($roles as $index => $rol) {
            foreach ($rol->permissions as $permission) {
                $permissions[] = $permission->name;
            }
        }

        if($user = Auth::user()) {
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
                            $permissions[] = $name;
                        }
                        break;
                }
            }
        }

        if (in_array('crud.all', $permissions)) {
            return $next($request);
        }

        $required_elements = count($required_permissions);
        $intersect_elements = count(array_intersect($required_permissions, $permissions));

        if ($required_elements != $intersect_elements) {
            ResponseController::set_errors('acceso restringido');
            return ResponseController::response('UNAUTHORIZED');
        }

        return $next($request);
    }
}
