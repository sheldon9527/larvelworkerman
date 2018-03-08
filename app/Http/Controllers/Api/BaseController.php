<?php
namespace App\Http\Controllers\Api;

use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class BaseController extends Controller
{
    use Helpers;

    // 返回错误的请求
    protected function errorBadRequest($errors = '')
    {
        if ($errors) {
            $errors = ['errors' => array_values(array_unique($errors))];
        }

        return $this->response->array($errors)->setStatusCode(400);
    }
}
