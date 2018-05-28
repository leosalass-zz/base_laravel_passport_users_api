<?php

namespace App\Http\Requests;

class RolStoreRequest extends GenericResposeRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:user_roles,name|string|max:45',
            'short_description' => 'required|string|max:255',
        ];
    }
}
