<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseController;
use App\Transformers\UserTransformer;
use App\Http\Requests\Api\User\IndexRequest;
use App\Models\User;

/**
 *
 */
class UserController extends BaseController
{
    public function index(IndexRequest $request)
    {
        $users = User::paginate(2);

        return $this->response->paginator($users, new UserTransformer);

        // $users = User::all();
        //
        // return $this->response->collection($users, new UserTransformer);
    }
    public function show($id)
    {
        $user = User::findOrFail($id);

        return $this->response->item($user, new UserTransformer);
    }

    public function me()
    {
        $user = auth()->user();
        
        return $this->response->item($user, new UserTransformer);
    }
}
