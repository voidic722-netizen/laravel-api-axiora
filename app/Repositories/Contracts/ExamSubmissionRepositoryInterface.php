<?php

namespace App\Repositories\Contracts;

use App\Models\ExamSubmission;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface ExamSubmissionRepositoryInterface
{
    /**
     * Source: ujian_repository.js — submitUjian/getMySubmission
     */
    public function findByExamAndUser(int $examId, int $userId): ?ExamSubmission;

    public function create(array $data): ExamSubmission;

    /**
     * Students belonging to any of the given classroom ids, with their classroom.
     * Source: ujian_repository.js — getSubmissions (siswaList)
     */
    public function studentsInClassrooms(array $classroomIds): Collection;

    /**
     * All submissions for an exam, with student and student's classroom eager-loaded.
     * Source: ujian_repository.js — getSubmissions
     */
    public function submissionsForExam(int $examId): Collection;
}
