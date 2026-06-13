<?php

namespace App\Services;

use App\Enums\RoleEnum;
use App\Exceptions\ApiException;
use App\Models\Exam;
use App\Models\User;
use App\Repositories\Contracts\ExamRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

class ExamService
{
    public function __construct(
        protected ExamRepositoryInterface $examRepository,
        protected CloudinaryService $cloudinaryService,
    ) {
    }

    /**
     * Source: ujian_repository.js — getUjianList
     * Note: correct-answer stripping for students is handled by
     * ExamStudentResource (Issue #17), not here.
     */
    public function list(User $user): Collection
    {
        return $this->examRepository->all($user);
    }

    /**
     * Source: ujian_repository.js — getUjianById
     */
    public function detail(int $id, User $user): Exam
    {
        $exam = $this->examRepository->find($id);

        if (!$exam) {
            throw new ApiException('Exam not found', 404);
        }

        if ($user->role === RoleEnum::STUDENT && !$this->classroomIdInList($user->classroom_id, $exam->classroom_ids)) {
            throw new ApiException('Exam not found', 404);
        }

        return $exam;
    }

    /**
     * Source: ujian_repository.js — createUjian
     *
     * @param array<string, UploadedFile> $questionImages keyed by "image_{question_id}"
     */
    public function create(array $data, array $questionImages = []): Exam
    {
        $data['questions'] = $this->hydrateQuestionImages($data['questions'], $questionImages);

        return $this->examRepository->create($data);
    }

    /**
     * Source: ujian_repository.js — updateUjian
     *
     * @param array<string, UploadedFile> $questionImages keyed by "image_{question_id}"
     */
    public function update(int $id, array $data, array $questionImages = []): Exam
    {
        $exam = $this->examRepository->find($id);

        if (!$exam) {
            throw new ApiException('Exam not found', 404);
        }

        $data['questions'] = $this->hydrateQuestionImages($data['questions'], $questionImages);

        return $this->examRepository->update($exam, $data);
    }

    /**
     * Source: ujian_repository.js — deleteUjian
     */
    public function delete(int $id): void
    {
        $exam = $this->examRepository->find($id);

        if (!$exam) {
            throw new ApiException('Exam not found', 404);
        }

        $this->examRepository->delete($exam);
    }

    /**
     * Source: ujian_repository.js — hydrateQuestionImages
     *
     * @param array<int, array> $questions
     * @param array<string, UploadedFile> $files keyed by "image_{question_id}"
     * @return array<int, array>
     */
    protected function hydrateQuestionImages(array $questions, array $files): array
    {
        return array_map(function (array $question) use ($files) {
            $fileKey = 'image_' . $question['id'];
            $file = $files[$fileKey] ?? null;

            $imagePath = (is_string($question['image'] ?? null) && trim($question['image']) !== '')
                ? $question['image']
                : null;

            if ($file instanceof UploadedFile) {
                $imagePath = $this->cloudinaryService->uploadImage($file, 'exams');
            }

            $question['image'] = $imagePath;

            return $question;
        }, $questions);
    }

    /**
     * Source: ujian_repository.js — getUjianList/getUjianById classroom check
     */
    protected function classroomIdInList(?int $classroomId, array $classroomIds): bool
    {
        if ($classroomId === null) {
            return false;
        }

        $normalized = array_map('strval', $classroomIds);

        return in_array((string) $classroomId, $normalized, true);
    }
}
