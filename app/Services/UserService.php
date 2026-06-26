<?php

namespace App\Services;

use App\Enums\PositionEnum;
use App\Enums\RoleEnum;
use App\Exceptions\ApiException;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected CloudinaryService $cloudinaryService,
    ) {}

    /**
     * Source: user_repository.js — getUsers
     */
    public function list(?int $role = null): Collection
    {
        return $this->userRepository->all($role);
    }

    public function detail(int $id): User
    {
        $user = $this->userRepository->find($id);
        if (! $user) {
            throw new ApiException('User not found', 404);
        }

        return $user;
    }

    /**
     * Source: user_repository.js — createUser
     */
    public function create(array $data, ?UploadedFile $image = null): User
    {
        $data = $this->applyDefaultPosition($data);
        $this->validateRoleFields($data);

        if ($this->userRepository->emailExists($data['email'])) {
            throw new ApiException('Email already exists', 409);
        }

        $this->assertDeanIsAvailable($data);

        if ($image) {
            $data['image'] = $this->cloudinaryService->uploadImage($image, 'users');
        }

        $data['password'] = Hash::make($data['password']);

        return $this->userRepository->create($data);
    }

    /**
     * Source: user_repository.js — updateUser
     */
    public function update(int $id, array $data, ?UploadedFile $image = null): User
    {
        $data = $this->applyDefaultPosition($data);
        $this->validateRoleFields($data);

        $user = $this->userRepository->find($id);

        if (! $user) {
            throw new ApiException('User not found', 404);
        }

        if (isset($data['email']) && $data['email'] !== $user->email) {
            if ($this->userRepository->emailExists($data['email'])) {
                throw new ApiException('Email already exists', 409);
            }
        }

        $this->assertDeanIsAvailable($data, excludeUserId: $id);

        if ($image) {
            $data['image'] = $this->cloudinaryService->uploadImage($image, 'users');
        }

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        return $this->userRepository->update($user, $data);
    }

    /**
     * Source: user_repository.js — deleteUser + user_controller.js — destroy
     */
    public function delete(int $id, User $authUser): void
    {
        if ($authUser->id === $id) {
            throw new ApiException('Cannot delete your own account', 403);
        }

        $user = $this->userRepository->find($id);

        if (! $user) {
            throw new ApiException('User not found', 404);
        }

        $first = $this->userRepository->firstRegisteredUser();

        if ($first && $first->id === $id) {
            throw new ApiException('Cannot delete the first registered user', 403);
        }

        // Revoke all Sanctum tokens so the deleted user is immediately logged out
        $user->tokens()->delete();

        $this->userRepository->delete($user);
    }

    /**
     * Source: user_repository.js — validateRoleFields
     */
    protected function validateRoleFields(array $data): void
    {
        $role = (int) $data['role'];

        if ($role === RoleEnum::LECTURER->value) {
            if (empty($data['position'])) {
                throw new ApiException('Position is required for Lecturer', 422);
            }
            if (empty($data['nidn'])) {
                throw new ApiException('NIDN is required for Lecturer', 422);
            }
            if (empty($data['faculty_id'])) {
                throw new ApiException('Faculty is required for Lecturer', 422);
            }
            if ($data['position'] === PositionEnum::LECTURER->value && empty($data['subject_id'])) {
                throw new ApiException('Subject is required for Lecturer position', 422);
            }
            if ($data['position'] === PositionEnum::DEPARTMENT_HEAD->value && empty($data['department_id'])) {
                throw new ApiException('Department is required for Department Head position', 422);
            }
        }

        if ($role === RoleEnum::STUDENT->value) {
            if (empty($data['faculty_id'])) {
                throw new ApiException('Faculty is required for Student', 422);
            }
            if (empty($data['department_id'])) {
                throw new ApiException('Department is required for Student', 422);
            }
            if (empty($data['classroom_id'])) {
                throw new ApiException('Classroom is required for Student', 422);
            }
            if (empty($data['nim'])) {
                throw new ApiException('NIM is required for Student', 422);
            }
        }
    }

    /**
     * Auto-assign the 'student' position for role 3 users (Issue #14, D8).
     */
    protected function applyDefaultPosition(array $data): array
    {
        if ((int) ($data['role'] ?? 0) === RoleEnum::STUDENT->value) {
            $data['position'] = PositionEnum::STUDENT->value;
        }

        return $data;
    }

    /**
     * Source: user_repository.js — dean uniqueness check
     */
    protected function assertDeanIsAvailable(array $data, ?int $excludeUserId = null): void
    {
        if (($data['position'] ?? null) !== PositionEnum::DEAN->value || empty($data['faculty_id'])) {
            return;
        }

        $existingDean = $this->userRepository->findDeanByFaculty((int) $data['faculty_id'], $excludeUserId);

        if ($existingDean) {
            throw new ApiException('This faculty already has a dean', 409);
        }
    }
}
