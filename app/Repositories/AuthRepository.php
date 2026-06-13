<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\AuthRepositoryInterface;

class AuthRepository implements AuthRepositoryInterface
{
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function emailExistsForOtherUser(string $email, int $excludeUserId): bool
    {
        return User::where('email', $email)
            ->where('id', '!=', $excludeUserId)
            ->exists();
    }

    public function updateProfile(User $user, array $data): User
    {
        $user->update([
            'name'  => $data['name'],
            'email' => $data['email'],
        ]);

        return $user->fresh();
    }
}
