<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface AuthRepositoryInterface
{
    /**
     * Find a user by email address.
     */
    public function findByEmail(string $email): ?User;

    /**
     * Check if an email is already taken by another user.
     * Source: auth_repository.js — updateProfile (Op.ne check)
     */
    public function emailExistsForOtherUser(string $email, int $excludeUserId): bool;

    /**
     * Update the authenticated user's name and email.
     * Source: auth_repository.js — updateProfile
     */
    public function updateProfile(User $user, array $data): User;
}
