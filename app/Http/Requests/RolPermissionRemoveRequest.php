<?php

namespace App\Http\Requests;
use Illuminate\Http\Request;

class RolPermissionRemoveRequest extends GenericResposeRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id_role' => 'required|integer|min:1|exists:user_roles,id',
            'id_permission' => 'required|integer|min:1|exists:user_permissions,id',
            'id_permission' => 'exists:user_role_has_user_permissions,id_permission,id_role,'.Request::get('id_role'),
            //'id_permission' => 'required|integer|min:1|exists:user_permissions,id',
        ];
    }
}
