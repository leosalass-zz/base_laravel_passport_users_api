<?php

namespace App\Http\Requests;

class UsersUpdateRequest extends GenericResposeRequest
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
            'password' => 'nullable|string|min:6|confirmed',
            'email' => 'nullable|string|email|max:255|unique:users',
            'name' => 'nullable|string|max:99',
            'state' => 'nullable|integer|min:1|exists:user_states,id',
        ];
    }
}
