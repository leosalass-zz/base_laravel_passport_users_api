<?php

namespace App\Http\Requests;
use Illuminate\Http\Request;

class UserPermissionRemoveRequest extends GenericResposeRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id_user' => 'required|integer|min:1|exists:users,id',
            'id_permission' => 'required|integer|min:1|exists:user_permissions,id',
            'id_permission' => 'exists:user_has_permissions,id_permission,id_user,'.Request::get('id_user'),
        ];
    }
}
