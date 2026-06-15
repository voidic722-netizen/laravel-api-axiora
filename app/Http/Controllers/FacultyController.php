<?php

namespace App\Http\Controllers;

use App\Http\Requests\Faculty\StoreFacultyRequest;
use App\Http\Resources\FacultyDetailResource;
use App\Http\Resources\FacultyResource;
use App\Services\FacultyService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FacultyController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected FacultyService $facultyService,
    ) {
    }

    /**
     * GET /api/faculties
     */
    public function index(): JsonResponse
    {
        return $this->success(FacultyResource::collection($this->facultyService->list()));
    }

    /**
     * GET /api/faculties/available-for-dean
     */
    public function availableForDean(Request $request): JsonResponse
    {
        $excludeUserId = $request->query('exclude_user_id');

        $faculties = $this->facultyService->availableForDean(
            $excludeUserId !== null ? (int) $excludeUserId : null
        );

        return $this->success(FacultyResource::collection($faculties));
    }

    /**
     * GET /api/faculties/{faculty}
     */
    public function show(int $faculty): JsonResponse
    {
        return $this->success(FacultyDetailResource::make($this->facultyService->detail($faculty)));
    }

    /**
     * POST /api/faculties
     */
    public function store(StoreFacultyRequest $request): JsonResponse
    {
        $faculty = $this->facultyService->create($request->validated(), $request->file('thumbnail'));

        return $this->success(FacultyResource::make($faculty), 'Faculty created', 201);
    }

    /**
     * PUT /api/faculties/{faculty}
     */
    public function update(StoreFacultyRequest $request, int $faculty): JsonResponse
    {
        $updated = $this->facultyService->update($faculty, $request->validated(), $request->file('thumbnail'));

        return $this->success(FacultyResource::make($updated), 'Faculty updated');
    }

    /**
     * DELETE /api/faculties/{faculty}
     */
    public function destroy(int $faculty): JsonResponse
    {
        $this->facultyService->delete($faculty);

        return $this->success(null, 'Faculty deleted');
    }
}
