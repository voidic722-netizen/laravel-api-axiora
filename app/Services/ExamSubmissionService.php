<?php

namespace App\Services;

use App\Enums\RoleEnum;
use App\Exceptions\ApiException;
use App\Models\ExamSubmission;
use App\Models\User;
use App\Repositories\Contracts\ExamRepositoryInterface;
use App\Repositories\Contracts\ExamSubmissionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ExamSubmissionService
{
    public function __construct(
        protected ExamRepositoryInterface $examRepository,
        protected ExamSubmissionRepositoryInterface $submissionRepository,
    ) {
    }

    /**
     * Source: ujian_repository.js — submitUjian
     *
     * @param array<string, mixed> $answers
     * @return array{score: int, correct_count: int, total_questions: int}
     */
    public function submit(int $examId, User $user, array $answers): array
    {
        $exam = $this->examRepository->find($examId);

        if (!$exam) {
            throw new ApiException('Exam not found', 404);
        }

        $now = now();

        // Issue #20 / D10: enforce available_at, previously unchecked.
        if ($now->lessThan($exam->available_at)) {
            throw new ApiException('This exam is not yet available', 422);
        }

        if ($now->greaterThan($exam->deadline_at)) {
            throw new ApiException('The exam submission deadline has passed', 422);
        }

        $existing = $this->submissionRepository->findByExamAndUser($exam->id, $user->id);

        if ($existing) {
            throw new ApiException('You have already submitted this exam', 409);
        }

        $questions = $exam->questions ?? [];
        $total = count($questions);
        $correct = 0;

        foreach ($questions as $question) {
            $studentAnswer = (string) ($answers[(string) $question['id']] ?? '');

            $correctOption = collect($question['options'] ?? [])
                ->first(fn ($option) => $option['isCorrect'] ?? false);

            if ($correctOption && $studentAnswer === (string) $correctOption['id']) {
                $correct++;
            }
        }

        $score = $total > 0 ? (int) round(($correct / $total) * 100) : 0;

        $this->submissionRepository->create([
            'exam_id'          => $exam->id,
            'user_id'          => $user->id,
            'answers'          => $answers,
            'score'            => $score,
            'correct_count'    => $correct,
            'total_questions'  => $total,
            'submitted_at'     => $now,
        ]);

        return [
            'score'           => $score,
            'correct_count'   => $correct,
            'total_questions' => $total,
        ];
    }

    /**
     * Source: ujian_repository.js — getMySubmission
     */
    public function mySubmission(int $examId, User $user): ?ExamSubmission
    {
        return $this->submissionRepository->findByExamAndUser($examId, $user->id);
    }

    /**
     * Source: ujian_repository.js — getSubmissions
     *
     * @return \Illuminate\Support\Collection<int, array{
     *     student_id: int, student_name: string, student_nim: ?string, student_email: string,
     *     classroom_id: ?int, classroom_name: ?string, is_submitted: bool, submission: ?ExamSubmission
     * }>
     */
    public function submissions(int $examId, User $user): \Illuminate\Support\Collection
    {
        if (!in_array($user->role, [RoleEnum::ADMIN, RoleEnum::LECTURER], true)) {
            throw new ApiException('Forbidden', 403);
        }

        $exam = $this->examRepository->find($examId);

        if (!$exam) {
            throw new ApiException('Exam not found', 404);
        }

        $classroomIds = array_values(array_filter(array_map('intval', $exam->classroom_ids)));

        $students = $this->submissionRepository->studentsInClassrooms($classroomIds);
        $submissions = $this->submissionRepository->submissionsForExam($exam->id);

        $submissionMap = $submissions->keyBy('user_id');

        return $students->map(function (User $student) use ($submissionMap) {
            $submission = $submissionMap->get($student->id);

            return [
                'student_id'     => $student->id,
                'student_name'   => $student->name,
                'student_nim'    => $student->nim,
                'student_email'  => $student->email,
                'classroom_id'   => $student->classroom_id,
                'classroom_name' => $student->classroom?->name,
                'is_submitted'   => (bool) $submission,
                'submission'     => $submission,
            ];
        });
    }
}
