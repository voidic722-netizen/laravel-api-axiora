<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    /**
     * List users, optionally filtered by role, with relations loaded.
     * Source: user_repository.js — getUsers
     */
    public function all(?int $role = null): Collection;

    public function find(int $id): ?User;

    public function findWithTrashed(int $id): ?User;

    /**
     * Check if an email is already taken (including soft-deleted rows).
     * Source: user_repository.js — createUser/updateUser (paranoid: false)
     */
    public function emailExists(string $email, ?int $excludeUserId = null): bool;

    /**
     * Find an existing dean for a faculty, optionally excluding a user.
     * Source: user_repository.js — createUser/updateUser dean uniqueness check
     */
    public function findDeanByFaculty(int $facultyId, ?int $excludeUserId = null): ?User;

    /**
     * The first registered user (lowest id), including soft-deleted.
     * Source: user_repository.js — deleteUser
     */
    public function firstRegisteredUser(): ?User;

    public function create(array $data): User;

    public function update(User $user, array $data): User;

    public function delete(User $user): bool;
}
