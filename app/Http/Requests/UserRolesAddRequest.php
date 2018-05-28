<?php

namespace App\Http\Requests;
use Illuminate\Http\Request;

class UserRolesAddRequest extends GenericResposeRequest
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
            'id_role' => 'required|integer|min:1|exists:user_roles,id|unique:user_has_roles,id_user|unique:user_has_roles,id_role',
        ];
    }
}
