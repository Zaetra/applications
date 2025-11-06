<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Contract\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UsersController extends Controller
{
    public function __construct(
        private UserRepositoryInterface $repository
    )
    {
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'data' => $this->repository->getAllUsers()
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $attributes = $request->only([
            'username',
            'email'
        ]);

        return response()->json(
            [
                'data' => $this->repository->createUser(
                    array_merge($attributes,['password' => Hash::make($request->input('password'))])
                )
            ],
            ResponseAlias::HTTP_CREATED
        );
    }

    public function show(User $user): JsonResponse
    {

        return response()->json([
            'data' => $user
        ]);
    }

    public function update(User $user, Request $request): JsonResponse
    {
        $attributes = $request->only([
            'username',
            'email'
        ]);

        return response()->json([
            'data' => $this->repository->updateUser($user, $attributes)
        ]);
    }

    public function destroy(User $user): JsonResponse
    {

        $this->repository->deleteUser($user);

        return response()->json(null, ResponseAlias::HTTP_NO_CONTENT);
    }
}
