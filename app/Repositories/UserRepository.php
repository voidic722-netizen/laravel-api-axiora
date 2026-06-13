<?php

namespace App\Repositories;

use App\Enums\PositionEnum;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function all(?int $role = null): Collection
    {
        $query = User::query()->with(['department', 'faculty', 'classroom', 'subject']);

        if ($role !== null) {
            $query->where('role', $role);
        }

        return $query->get();
    }

    public function find(int $id): ?User
    {
        return User::find($id);
    }

    public function findWithTrashed(int $id): ?User
    {
        return User::withTrashed()->find($id);
    }

    public function emailExists(string $email, ?int $excludeUserId = null): bool
    {
        $query = User::withTrashed()->where('email', $email);

        if ($excludeUserId !== null) {
            $query->where('id', '!=', $excludeUserId);
        }

        return $query->exists();
    }

    public function findDeanByFaculty(int $facultyId, ?int $excludeUserId = null): ?User
    {
        $query = User::where('faculty_id', $facultyId)
            ->where('position', PositionEnum::DEAN);

        if ($excludeUserId !== null) {
            $query->where('id', '!=', $excludeUserId);
        }

        return $query->first();
    }

    public function firstRegisteredUser(): ?User
    {
        return User::withTrashed()->orderBy('id', 'asc')->first();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);

        return $user->fresh();
    }

    public function delete(User $user): bool
    {
        return (bool) $user->delete();
    }
}
