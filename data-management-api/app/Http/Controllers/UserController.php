<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function index(): UserCollection
    {
        $cacheKey = 'user:filters_' . md5(json_encode(request()->all()));

        $users = Cache::tags(['user'])->remember($cacheKey, now()->addMinutes(10), function () {
            return User::filter()->paginate();
        });

        return new UserCollection($users);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResponse
    {
        return $this->response(Response::HTTP_OK, new UserResource($user));
    }
}
