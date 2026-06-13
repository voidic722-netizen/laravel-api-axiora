<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\User;
use App\Repositories\Contracts\AuthRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(
        protected AuthRepositoryInterface $authRepository,
    ) {
    }

    /**
     * Source: auth_controller.js — login
     *
     * @return array{user: User, token: string}
     */
    public function login(string $email, string $password): array
    {
        $user = $this->authRepository->findByEmail($email);

        if (!$user || !Hash::check($password, $user->password)) {
            throw new ApiException('Invalid email or password', 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    /**
     * Source: auth_controller.js — logout
     */
    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

    /**
     * Source: auth_controller.js — me
     */
    public function me(User $user): User
    {
        return $user->load(['faculty', 'department', 'classroom', 'subject']);
    }

    /**
     * Source: auth_repository.js — updateProfile
     */
    public function updateProfile(User $user, array $data): User
    {
        if ($this->authRepository->emailExistsForOtherUser($data['email'], $user->id)) {
            throw new ApiException('Email already exists', 409);
        }

        return $this->authRepository->updateProfile($user, $data);
    }
}
