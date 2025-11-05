<?php

namespace App\Services;

use App\Models\User;
use Hash;

class AuthService
{
    public function login($email, $password): array
    {
        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw new \Exception('Credenciales incorrectas');
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }
}
