<?php

namespace App\Http\Controllers;

use App\Http\Requests\Subject\StoreSubjectRequest;
use App\Http\Resources\SubjectResource;
use App\Services\SubjectService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class SubjectController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected SubjectService $subjectService,
    ) {
    }

    /**
     * GET /api/subjects
     * Source: mata_pelajaran_controller.js — index
     */
    public function index(): JsonResponse
    {
        return $this->success(SubjectResource::collection($this->subjectService->list()));
    }

    /**
     * GET /api/subjects/{subject}
     * Source: mata_pelajaran_controller.js — show
     */
    public function show(int $subject): JsonResponse
    {
        return $this->success(SubjectResource::make($this->subjectService->detail($subject)));
    }

    /**
     * POST /api/subjects
     * Source: mata_pelajaran_controller.js — create
     */
    public function store(StoreSubjectRequest $request): JsonResponse
    {
        $subject = $this->subjectService->create($request->validated(), $request->file('thumbnail'));

        return $this->success(SubjectResource::make($subject), 'Subject created', 201);
    }

    /**
     * PUT /api/subjects/{subject}
     * Source: mata_pelajaran_controller.js — update
     */
    public function update(StoreSubjectRequest $request, int $subject): JsonResponse
    {
        $updated = $this->subjectService->update($subject, $request->validated(), $request->file('thumbnail'));

        return $this->success(SubjectResource::make($updated), 'Subject updated');
    }

    /**
     * DELETE /api/subjects/{subject}
     * Source: mata_pelajaran_controller.js — destroy
     */
    public function destroy(int $subject): JsonResponse
    {
        $this->subjectService->delete($subject);

        return $this->success(null, 'Subject deleted');
    }
}
