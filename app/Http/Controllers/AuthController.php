<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected AuthService $authService,
    ) {
    }

    /**
     * POST /api/auth/login
     * Source: auth_controller.js — login
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login(
            $request->validated('email'),
            $request->validated('password'),
        );

        return $this->success([
            'user'  => UserResource::make($result['user']),
            'token' => $result['token'],
        ], 'Login successful');
    }

    /**
     * POST /api/auth/logout
     * Source: auth_controller.js — logout
     */
    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return $this->success(null, 'Logout successful');
    }

    /**
     * GET /api/auth/me
     * Source: auth_controller.js — me
     */
    public function me(Request $request): JsonResponse
    {
        $user = $this->authService->me($request->user());

        return $this->success(UserResource::make($user));
    }

    /**
     * PATCH /api/auth/profile
     * Source: auth_controller.js — updateProfile
     */
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $user = $this->authService->updateProfile($request->user(), $request->validated());

        return $this->success(UserResource::make($user), 'Profile updated');
    }
}
