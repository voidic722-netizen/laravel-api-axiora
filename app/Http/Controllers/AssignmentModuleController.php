<?php

namespace App\Http\Controllers;

use App\Services\AssignmentService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class AssignmentModuleController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected AssignmentService $assignmentService,
    ) {
    }

    /**
     * DELETE /api/assignment-modules/{module}
     * Source: tugas_controller.js — deleteTugasModul
     */
    public function destroy(int $module): JsonResponse
    {
        $this->assignmentService->deleteModule($module);

        return $this->success(null, 'Module deleted');
    }
}
