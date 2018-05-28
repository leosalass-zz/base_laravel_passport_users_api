<?php

namespace App\Http\Requests;

class RolPermissionAddRequest extends GenericResposeRequest
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
            'id_permission' => 'required|integer|min:1|exists:user_permissions,id|unique:user_role_has_user_permissions,id_permission|unique:user_role_has_user_permissions,id_role',
        ];
    }
}
