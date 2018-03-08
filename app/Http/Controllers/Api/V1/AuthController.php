<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\User\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Authorization;
use App\Transformers\AuthorizationTransformer;

/**
 *
 */
class AuthController extends BaseController
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);
        if (!$token = \JWTAuth::attempt($credentials)) {
            return $this->response->error('账号密码不正确', 404);
        }
        $authorization = new Authorization($token);

        return $this->response->item($authorization, new AuthorizationTransformer())
             ->setStatusCode(201);
    }
}
