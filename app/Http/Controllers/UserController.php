<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected UserService $userService,
    ) {
    }

    /**
     * GET /api/users
     */
    public function index(Request $request): JsonResponse
    {
        $role = $request->query('role');

        $users = $this->userService->list($role !== null ? (int) $role : null);

        return $this->success(UserResource::collection($users));
    }

    /**
     * POST /api/users
     */
    public function store(CreateUserRequest $request): JsonResponse
    {
        $user = $this->userService->create($request->validated(), $request->file('image'));

        return $this->success(UserResource::make($user), 'User created', 201);
    }

    /**
     * PUT /api/users/{user}
     */
    public function update(UpdateUserRequest $request, int $user): JsonResponse
    {
        $updated = $this->userService->update($user, $request->validated(), $request->file('image'));

        return $this->success(UserResource::make($updated), 'User updated');
    }

    /**
     * DELETE /api/users/{user}
     */
    public function destroy(Request $request, int $user): JsonResponse
    {
        $this->userService->delete($user, $request->user());

        return $this->success(null, 'User deleted');
    }
}
