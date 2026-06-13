<?php

namespace App\Repositories;

use App\Enums\RoleEnum;
use App\Models\AssignmentSubmission;
use App\Models\User;
use App\Repositories\Contracts\AssignmentSubmissionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AssignmentSubmissionRepository implements AssignmentSubmissionRepositoryInterface
{
    public function findByAssignmentAndUser(int $assignmentId, int $userId): ?AssignmentSubmission
    {
        return AssignmentSubmission::where('assignment_id', $assignmentId)
            ->where('user_id', $userId)
            ->first();
    }

    public function find(int $id): ?AssignmentSubmission
    {
        return AssignmentSubmission::find($id);
    }

    public function create(array $data): AssignmentSubmission
    {
        return AssignmentSubmission::create($data);
    }

    public function update(AssignmentSubmission $submission, array $data): AssignmentSubmission
    {
        $submission->update($data);

        return $submission->fresh();
    }

    public function studentsInClassrooms(array $classroomIds): Collection
    {
        if (empty($classroomIds)) {
            return new Collection();
        }

        return User::where('role', RoleEnum::STUDENT)
            ->whereIn('classroom_id', $classroomIds)
            ->with('classroom')
            ->orderBy('classroom_id')
            ->orderBy('name')
            ->get();
    }

    public function submissionsForAssignment(int $assignmentId): Collection
    {
        return AssignmentSubmission::where('assignment_id', $assignmentId)->get();
    }
}
