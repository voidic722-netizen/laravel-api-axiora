<?php

namespace App\Repositories\Contracts;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface ClassroomRepositoryInterface
{
    /**
     * List classrooms. Students see only their own classroom.
     * Source: kelas_repository.js — getKelasList
     */
    public function all(User $user): Collection;

    /**
     * Classroom detail with department, faculty, semester, subject,
     * lecturers, students, and schedules eager-loaded.
     * Source: kelas_repository.js — getKelasDetail
     */
    public function findDetail(int $id): ?Classroom;

    public function find(int $id): ?Classroom;

    /**
     * Find a classroom by its unique combination (including soft-deleted).
     * Source: kelas_repository.js — createKelas duplicate check
     */
    public function findDuplicate(string $name, int $departmentId, int $semesterId, int $subjectId): ?Classroom;

    public function create(array $data): Classroom;

    /**
     * Assignments whose classroom_ids JSON array contains this classroom id.
     * Source: kelas_repository.js — getKelasDetail (tasks filter, Issue #07 fix)
     */
    public function assignmentsForClassroom(int $classroomId): Collection;

    /**
     * Exams whose classroom_ids JSON array contains this classroom id.
     * Source: kelas_repository.js — getKelasDetail (exams filter, Issue #07 fix)
     */
    public function examsForClassroom(int $classroomId): Collection;
}
