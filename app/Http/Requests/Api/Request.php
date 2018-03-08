<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class Request extends FormRequest
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
        $errors = $validator->messages()->all();

        throw new HttpResponseException($this->errorBadRequest($errors));
    }

    public function errorBadRequest($errors)
    {
        if ($errors) {
            $errors = ['errors' => array_values(array_unique($errors))];
        }

        return response()->json($errors)->setStatusCode(400);
    }
}
