<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExamSubmission\SubmitExamRequest;
use App\Http\Resources\ExamSubmissionListResource;
use App\Http\Resources\ExamSubmissionResource;
use App\Services\ExamSubmissionService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ExamSubmissionController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected ExamSubmissionService $examSubmissionService,
    ) {
    }

    /**
     * POST /api/exams/{exam}/submit
     */
    public function submit(SubmitExamRequest $request, int $exam): JsonResponse
    {
        $result = $this->examSubmissionService->submit(
            $exam,
            $request->user(),
            $request->validated('answers'),
        );

        return $this->success($result, 'Exam submitted', 201);
    }

    /**
     * GET /api/exams/{exam}/my-submission
     */
    public function mySubmission(Request $request, int $exam): JsonResponse
    {
        $submission = $this->examSubmissionService->mySubmission($exam, $request->user());

        return $this->success($submission ? ExamSubmissionResource::make($submission) : null);
    }

    /**
     * GET /api/exams/{exam}/submissions
     */
    public function submissions(Request $request, int $exam): JsonResponse
    {
        $submissions = $this->examSubmissionService->submissions($exam, $request->user());

        return $this->success(ExamSubmissionListResource::collection($submissions));
    }
}
