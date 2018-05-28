<?php

namespace App\Http\Requests;

class PermissionDestroyRequest extends GenericResposeRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id_permission' => 'required|integer|exists:user_permissions,id,deleted_at,NULL',
        ];
    }
}
