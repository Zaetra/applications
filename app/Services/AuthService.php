<?php

namespace App\Services;

use App\Contract\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Constructor del AuthService
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    /**
     * Autenticar un usuario y generar token
     *
     * @param string $email
     * @param string $password
     * @return array
     * @throws \Exception
     */
    public function login(string $email, string $password): array
    {
        // Usar el repositorio en lugar del modelo directamente
        $user = $this->userRepository->findByEmail($email);

        if (!$user || !Hash::check($password, $user->password)) {
            throw new \Exception('Credenciales incorrectas');
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    /**
     * Registrar un nuevo usuario
     *
     * @param array $data
     * @return array
     */
    public function register(array $data): array
    {
        // Crear usuario usando el repositorio (ya hashea la contraseña)
        $user = $this->userRepository->createUser($data);

        $token = $user->createToken('api_token')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }
}
