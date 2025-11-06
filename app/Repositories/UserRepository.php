<?php
namespace App\Repositories;

use App\Contract\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Collection;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getAllUsers(): Collection
    {
        return User::all();
    }

    public function getUserById(User $user): User
    {
        return $user;
    }

    public function deleteUser(User $user): void
    {
        $user->delete();
    }

    public function createUser(array $attributes): User
    {
        return User::create($attributes);
    }

    public function updateUser(User $user, array $attributes): bool
    {
        return $user->update($attributes);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->findByField('email', $email);
    }

    public function getActiveUsers(): Collection
    {
        return $this->findWhere(['status' => 'active']);
    }

    public function emailExists(string $email): bool
    {
        return $this->findByEmail($email) !== null;
    }
}
