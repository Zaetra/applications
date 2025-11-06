<?php

namespace App\Contract;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function findByEmail(string $email): ?User;
    public function createUser(array $attributes): User;
    public function getActiveUsers(): \Illuminate\Database\Eloquent\Collection;
    public function emailExists(string $email): bool;
}
