<?php

namespace App\Http\Controllers;

use App\Http\Requests\Assignment\StoreAssignmentRequest;
use App\Http\Requests\Assignment\UpdateAssignmentRequest;
use App\Http\Resources\AssignmentResource;
use App\Services\AssignmentService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AssignmentController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected AssignmentService $assignmentService,
    ) {
    }

    /**
     * GET /api/assignments
     * Source: tugas_controller.js — index
     * Students only see assignments targeting their classroom.
     */
    public function index(Request $request): JsonResponse
    {
        return $this->success(
            AssignmentResource::collection($this->assignmentService->list($request->user()))
        );
    }

    /**
     * GET /api/assignments/{assignment}
     * Source: tugas_controller.js — show
     */
    public function show(Request $request, int $assignment): JsonResponse
    {
        $detail = $this->assignmentService->detail($assignment, $request->user());

        return $this->success(AssignmentResource::make($detail));
    }

    /**
     * POST /api/assignments
     * Source: tugas_controller.js — create
     */
    public function store(StoreAssignmentRequest $request): JsonResponse
    {
        $assignment = $this->assignmentService->create(
            $request->validated(),
            $request->file('modules', [])
        );

        return $this->success(AssignmentResource::make($assignment), 'Assignment created', 201);
    }

    /**
     * PUT /api/assignments/{assignment}
     * Source: tugas_controller.js — update
     */
    public function update(UpdateAssignmentRequest $request, int $assignment): JsonResponse
    {
        $updated = $this->assignmentService->update(
            $assignment,
            $request->validated(),
            $request->file('modules', [])
        );

        return $this->success(AssignmentResource::make($updated), 'Assignment updated');
    }

    /**
     * DELETE /api/assignments/{assignment}
     * Source: tugas_controller.js — destroy
     */
    public function destroy(int $assignment): JsonResponse
    {
        $this->assignmentService->delete($assignment);

        return $this->success(null, 'Assignment deleted');
    }
}
