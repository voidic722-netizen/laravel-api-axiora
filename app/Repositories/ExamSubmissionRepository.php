<?php

namespace App\Repositories;

use App\Enums\RoleEnum;
use App\Models\ExamSubmission;
use App\Models\User;
use App\Repositories\Contracts\ExamSubmissionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ExamSubmissionRepository implements ExamSubmissionRepositoryInterface
{
    public function findByExamAndUser(int $examId, int $userId): ?ExamSubmission
    {
        return ExamSubmission::where('exam_id', $examId)
            ->where('user_id', $userId)
            ->first();
    }

    public function create(array $data): ExamSubmission
    {
        return ExamSubmission::create($data);
    }

    public function studentsInClassrooms(array $classroomIds): Collection
    {
        if (empty($classroomIds)) {
            return new Collection();
        }

        return User::where('role', RoleEnum::STUDENT)
            ->whereIn('classroom_id', $classroomIds)
            ->with('classroom')
            ->orderBy('name')
            ->get();
    }

    public function submissionsForExam(int $examId): Collection
    {
        return ExamSubmission::where('exam_id', $examId)
            ->with('student.classroom')
            ->orderBy('submitted_at', 'desc')
            ->get();
    }
}
