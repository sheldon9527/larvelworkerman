<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['namespace' => 'App\Http\Controllers\Api\V1'], function ($api) {
    $api->group(['middleware' => 'api.auth'], function ($api) {
        $api->get('me', [
            'as' => 'users.show', 'uses' => 'UserController@me'
        ]);
    });
    $api->post('login', [
        'as' => 'auth.login', 'uses' => 'AuthController@login'
    ]);
    $api->get('users/{id}', [
        'as' => 'users.show', 'uses' => 'UserController@show'
    ]);
    $api->get('users', [
        'as' => 'users.index', 'uses' => 'UserController@index'
    ]);

    $api->get('login', [
        'as' => 'mini.program.login', 'uses' => 'ChatController@login'
    ]);
    $api->get('token', [
        'as' => 'mini.program.token', 'uses' => 'ChatController@getAccessInfo'
    ]);
});
