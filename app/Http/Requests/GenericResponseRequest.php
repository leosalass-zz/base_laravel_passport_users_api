<?php

namespace App\Http\Requests;

use App\Http\Controllers\ResponseController;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class GenericResposeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        ResponseController::set_errors(true);
        ResponseController::set_messages($validator->errors()->toArray());
        throw new HttpResponseException(ResponseController::response('BAD REQUEST'));
    }
}
