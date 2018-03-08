<?php

namespace App\Http\Requests\Api\User;

use App\Http\Requests\Api\Request;

class IndexRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules =  [
            'name' => 'string',
        ];
        return $rules;
    }

    public function messages()
    {
        return [
            'name.integer' => '名字为字符串',
        ];
    }
}
