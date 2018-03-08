<?php

namespace App\Http\Requests\Api\Chat;

use App\Http\Requests\Api\Request;

class TokenRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules =  [
            'token' => 'required',
        ];
        return $rules;
    }

    public function messages()
    {
        return [
            'token.required' => 'token不能为空',
        ];
    }
}
