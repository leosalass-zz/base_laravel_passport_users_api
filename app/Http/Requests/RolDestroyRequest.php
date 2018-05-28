<?php

namespace App\Http\Requests;

class RolDestroyRequest extends GenericResposeRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id_role' => 'required|integer|exists:user_roles,id,deleted_at,NULL',
        ];
    }
}
