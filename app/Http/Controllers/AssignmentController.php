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
     */
    public function index(Request $request): JsonResponse
    {
        return $this->success(
            AssignmentResource::collection($this->assignmentService->list($request->user()))
        );
    }

    /**
     * GET /api/assignments/{assignment}
     */
    public function show(Request $request, int $assignment): JsonResponse
    {
        $detail = $this->assignmentService->detail($assignment, $request->user());

        return $this->success(AssignmentResource::make($detail));
    }

    /**
     * POST /api/assignments
     */
    public function store(StoreAssignmentRequest $request): JsonResponse
{
    \Log::debug('Raw modules before validation', [
        'has_modules' => $request->hasFile('modules'),
        'files_raw'   => array_map(function ($f) {
            return [
                'name'    => $f->getClientOriginalName(),
                'error'   => $f->getError(),
                'is_valid' => $f->isValid(),
            ];
        }, $request->file('modules', [])),
    ]);

    $assignment = $this->assignmentService->create(
        $request->validated(),
        $request->file('modules', [])
    );

    return $this->success(AssignmentResource::make($assignment), 'Assignment created', 201);
}

    /**
     * PUT /api/assignments/{assignment}
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
     */
    public function destroy(int $assignment): JsonResponse
    {
        $this->assignmentService->delete($assignment);

        return $this->success(null, 'Assignment deleted');
    }
}
