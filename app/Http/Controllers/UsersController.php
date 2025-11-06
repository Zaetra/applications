<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Contract\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PharIo\Version\Exception;
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
        try {
            return response()->json([
                'data' => $this->repository->getAllUsers()
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

    }

    public function store(Request $request): JsonResponse
    {
        $attributes = $request->only([
            'username',
            'email'
        ]);

        try {
            return response()->json(
                [
                    'data' => $this->repository->createUser(
                        array_merge($attributes,['password' => Hash::make($request->input('password'))])
                    )
                ],
                ResponseAlias::HTTP_CREATED
            );
        } catch (Exception $exception) {
            return  response()->json([
                'error' => $exception->getMessage()
            ], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $user = $this->repository->findById($id);
            return response()->json([
                'data' => $user
            ],
                ResponseAlias::HTTP_ACCEPTED
            );
        } catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], ResponseAlias::HTTP_NOT_FOUND);
        }
    }

    public function update(User $user, Request $request): JsonResponse
    {
        $attributes = $request->only([
            'username',
            'email'
        ]);

        try {
            return response()->json([
                'data' => $this->repository->updateUser($user, $attributes)
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }
}
