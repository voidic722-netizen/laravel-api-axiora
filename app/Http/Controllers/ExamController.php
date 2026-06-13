<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Http\Requests\Exam\StoreExamRequest;
use App\Http\Requests\Exam\UpdateExamRequest;
use App\Http\Resources\ExamResource;
use App\Http\Resources\ExamStudentResource;
use App\Models\Exam;
use App\Services\ExamService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ExamController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected ExamService $examService,
    ) {
    }

    /**
     * GET /api/exams
     * Source: ujian_controller.js — index
     * Students receive answers stripped (Issue #17).
     */
    public function index(Request $request): JsonResponse
    {
        $exams = $this->examService->list($request->user());

        return $this->success($this->examCollection($exams, $request));
    }

    /**
     * GET /api/exams/{exam}
     * Source: ujian_controller.js — show
     */
    public function show(Request $request, int $exam): JsonResponse
    {
        $detail = $this->examService->detail($exam, $request->user());

        return $this->success($this->examResource($detail, $request));
    }

    /**
     * POST /api/exams
     * Source: ujian_controller.js — create
     */
    public function store(StoreExamRequest $request): JsonResponse
    {
        $exam = $this->examService->create(
            $request->validated(),
            $this->extractQuestionImages($request),
        );

        return $this->success(ExamResource::make($exam), 'Exam created', 201);
    }

    /**
     * PUT /api/exams/{exam}
     * Source: ujian_controller.js — update
     */
    public function update(UpdateExamRequest $request, int $exam): JsonResponse
    {
        $updated = $this->examService->update(
            $exam,
            $request->validated(),
            $this->extractQuestionImages($request),
        );

        return $this->success(ExamResource::make($updated), 'Exam updated');
    }

    /**
     * DELETE /api/exams/{exam}
     * Source: ujian_controller.js — destroy
     */
    public function destroy(int $exam): JsonResponse
    {
        $this->examService->delete($exam);

        return $this->success(null, 'Exam deleted');
    }

    /**
     * Issue #17: students never receive `isCorrect`.
     */
    protected function examResource(Exam $exam, Request $request): JsonResource
    {
        return $request->user()->role === RoleEnum::STUDENT
            ? ExamStudentResource::make($exam)
            : ExamResource::make($exam);
    }

    protected function examCollection(\Illuminate\Database\Eloquent\Collection $exams, Request $request): JsonResource
    {
        return $request->user()->role === RoleEnum::STUDENT
            ? ExamStudentResource::collection($exams)
            : ExamResource::collection($exams);
    }

    /**
     * G2: question images arrive as dynamic multipart keys "image_{question_id}".
     * Validated here as image|max:2048 (not present in the original Express
     * validators, which performed no validation on these files at all).
     *
     * @return array<string, \Illuminate\Http\UploadedFile>
     */
    protected function extractQuestionImages(Request $request): array
    {
        $images = [];

        foreach ($request->allFiles() as $key => $file) {
            if (!str_starts_with($key, 'image_')) {
                continue;
            }

            $validator = Validator::make(
                [$key => $file],
                [$key => ['image', 'max:2048']],
            );

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $images[$key] = $file;
        }

        return $images;
    }
}
