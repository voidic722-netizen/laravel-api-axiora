<?php

namespace App\Http\Controllers;

use App\Http\Requests\Department\StoreDepartmentRequest;
use App\Http\Resources\DepartmentDetailResource;
use App\Http\Resources\DepartmentResource;
use App\Services\DepartmentService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class DepartmentController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected DepartmentService $departmentService,
    ) {
    }

    /**
     * GET /api/departments
     * Source: jurusan_controller.js — index
     */
    public function index(): JsonResponse
    {
        return $this->success(DepartmentResource::collection($this->departmentService->list()));
    }

    /**
     * GET /api/departments/{department}
     * Source: jurusan_controller.js — show
     */
    public function show(int $department): JsonResponse
    {
        return $this->success(DepartmentDetailResource::make($this->departmentService->detail($department)));
    }

    /**
     * POST /api/departments
     * Source: jurusan_controller.js — create
     */
    public function store(StoreDepartmentRequest $request): JsonResponse
    {
        $department = $this->departmentService->create($request->validated(), $request->file('thumbnail'));

        return $this->success(DepartmentResource::make($department), 'Department created', 201);
    }

    /**
     * PUT /api/departments/{department}
     * Source: jurusan_controller.js — update
     */
    public function update(StoreDepartmentRequest $request, int $department): JsonResponse
    {
        $updated = $this->departmentService->update($department, $request->validated(), $request->file('thumbnail'));

        return $this->success(DepartmentResource::make($updated), 'Department updated');
    }

    /**
     * DELETE /api/departments/{department}
     * Source: jurusan_controller.js — destroy
     */
    public function destroy(int $department): JsonResponse
    {
        $this->departmentService->delete($department);

        return $this->success(null, 'Department deleted');
    }
}
