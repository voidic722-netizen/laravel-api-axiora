<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignmentSubmission\GradeSubmissionRequest;
use App\Http\Requests\AssignmentSubmission\SubmitAssignmentRequest;
use App\Http\Resources\AssignmentSubmissionListResource;
use App\Http\Resources\AssignmentSubmissionResource;
use App\Services\AssignmentSubmissionService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AssignmentSubmissionController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected AssignmentSubmissionService $submissionService,
    ) {
    }

    /**
     * POST /api/assignments/{assignment}/submit
     * Source: tugas_pengumpulan_controller.js — submit
     */
    public function submit(SubmitAssignmentRequest $request, int $assignment): JsonResponse
    {
        $submission = $this->submissionService->submit(
            $assignment,
            $request->user(),
            $request->file('files'),
        );

        return $this->success(AssignmentSubmissionResource::make($submission), 'Submission saved', 201);
    }

    /**
     * GET /api/assignments/{assignment}/my-submission
     * Source: tugas_pengumpulan_controller.js — getMySubmission
     */
    public function mySubmission(Request $request, int $assignment): JsonResponse
    {
        $submission = $this->submissionService->mySubmission($assignment, $request->user());

        return $this->success($submission ? AssignmentSubmissionResource::make($submission) : null);
    }

    /**
     * GET /api/assignments/{assignment}/submissions
     * Source: tugas_pengumpulan_controller.js — getSubmissionsByTugas
     */
    public function submissions(Request $request, int $assignment): JsonResponse
    {
        $submissions = $this->submissionService->submissionsForAssignment($assignment, $request->user());

        return $this->success(AssignmentSubmissionListResource::collection($submissions));
    }

    /**
     * POST /api/assignment-submissions/{submission}/grade
     * Source: tugas_pengumpulan_controller.js — gradeSubmission
     */
    public function grade(GradeSubmissionRequest $request, int $submission): JsonResponse
    {
        $graded = $this->submissionService->grade(
            $submission,
            (int) $request->validated('grade'),
            $request->validated('feedback'),
            $request->user(),
        );

        return $this->success(AssignmentSubmissionResource::make($graded), 'Submission graded');
    }
}
