<?php

namespace App\Http\Controllers;

use App\Http\Requests\Semester\StoreSemesterRequest;
use App\Http\Resources\SemesterResource;
use App\Services\SemesterService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class SemesterController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected SemesterService $semesterService,
    ) {
    }

    /**
     * GET /api/semesters
     */
    public function index(): JsonResponse
    {
        return $this->success(SemesterResource::collection($this->semesterService->list()));
    }

    /**
     * POST /api/semesters
     */
    public function store(StoreSemesterRequest $request): JsonResponse
    {
        $semester = $this->semesterService->create($request->validated());

        return $this->success(SemesterResource::make($semester), 'Semester created', 201);
    }

    /**
     * PUT /api/semesters/{semester}
     */
    public function update(StoreSemesterRequest $request, int $semester): JsonResponse
    {
        $updated = $this->semesterService->update($semester, $request->validated());

        return $this->success(SemesterResource::make($updated), 'Semester updated');
    }

    /**
     * DELETE /api/semesters/{semester}
     */
    public function destroy(int $semester): JsonResponse
    {
        $this->semesterService->delete($semester);

        return $this->success(null, 'Semester deleted');
    }
}
