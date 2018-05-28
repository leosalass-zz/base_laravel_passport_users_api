<?php

namespace App\Http\Requests;
use Illuminate\Http\Request;

class UserRolesRemoveRequest extends GenericResposeRequest
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
            'id_role' => 'required|integer|min:1|exists:user_roles,id',
            'id_role' => 'exists:user_has_roles,id_role,id_user,'.Request::get('id_user'),
        ];
    }
}
