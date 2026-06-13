<?php

namespace App\Repositories\Contracts;

use App\Models\AssignmentSubmission;
use Illuminate\Database\Eloquent\Collection;

interface AssignmentSubmissionRepositoryInterface
{
    /**
     * Source: tugas_pengumpulan_repository.js — upsertSubmission/getMySubmission
     */
    public function findByAssignmentAndUser(int $assignmentId, int $userId): ?AssignmentSubmission;

    public function find(int $id): ?AssignmentSubmission;

    public function create(array $data): AssignmentSubmission;

    public function update(AssignmentSubmission $submission, array $data): AssignmentSubmission;

    /**
     * Students belonging to any of the given classroom ids, with their classroom.
     * Source: tugas_pengumpulan_repository.js — getSubmissionsByTugas (siswa list)
     */
    public function studentsInClassrooms(array $classroomIds): Collection;

    /**
     * All submissions for an assignment, keyed by user_id.
     * Source: tugas_pengumpulan_repository.js — getSubmissionsByTugas
     */
    public function submissionsForAssignment(int $assignmentId): Collection;
}
