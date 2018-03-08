<?php

namespace App\Http\Requests\Api\User;

use App\Http\Requests\Api\Request;

class LoginRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules =  [
            'password' => 'required|alpha_num|between:6,20',
            'email'    => 'required|email',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'password.required' => '密码不能为空',
            'password.alpha_num' => '密码必须为字母和数字类型',
            'password.between' => '密码必须为6-20位',
            'email.required' => '邮箱不能为空',
            'email.email' => '邮箱类型不正确',
        ];
    }
}
