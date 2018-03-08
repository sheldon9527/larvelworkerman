<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Transformers\ChatTransformer;
use App\Models\ChatToken;
use GuzzleHttp\Client;
use App\Http\Requests\Api\Chat\TokenRequest;

class ChatController extends BaseController
{
    /**
     * @apiGroup chat
     * @apiDescription 根据小程序code,到后台换取 openId, sessionKey, unionId,然后在自己服务器上储存，并返回自己的token
     *
     * @api {get} /api/login 获取自定义token
     * @apiVersion 0.2.0
     * @apiPermission none
     * @apiSuccessExample {json} Success-Response 获取token数据:
     * HTTP/1.1 200 OK
     * {
     *  data: {
     *   token: "e3ae00031d3c540f83d454c904de2c99",
     *   expired_at: 43200
     *  }
     * }
     */
    public function login(Request $request)
    {
        $code = $request->get('code');
        $http = new Client();
        $response = $http->request('GET', 'https://api.weixin.qq.com/sns/jscode2session', [
            'query' => [
                'appid' => env('APPID'),
                'secret'=> env('APPAECRET'),
                'js_code'=>$code,
                'grant_type'=>env('GRENT_TYPE')
            ]
        ]);
        $data = json_decode((string)$response->getBody(), true);
        $appid = $data['openid'];
        $sessionKey = $data['session_key'];
        $token = md5($appid);
        $expiresAt = 3600*12;
        $result['data']['token'] =  $token;
        $result['data']['expired_at'] = $expiresAt;
        //放入缓存
        \Cache::put($token, $data, $expiresAt);

        return $result;
    }
    /**
     * @apiGroup chat
     * @apiDescription 根据自定义token,到后台换取 openId, sessionKey, unionId
     *
     * @api {get} /api/token 获取session_token信息
     * @apiVersion 0.2.0
     * @apiPermission none
     * @apiParam {string} token 根据token获取访问信息
     * @apiSuccessExample {json} Success-Response 获取访问信息数据:
     * HTTP/1.1 200 OK
     * {
     *  data: {
     *   session_key: "K5OJInyXZxMuIiNBzmnuYg==",
     *   openid: "oe_sI4zIksbeKN0A3GaI8KchemO4"
     *  }
     * }
     */
    public function getAccessInfo(TokenRequest $request)
    {
        $token = $request->get('token');
        $data = \Cache::get($token);
        $result['data'] = $data;

        return $result;
    }
}
