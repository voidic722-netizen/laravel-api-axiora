<?php

namespace App\Repositories;

use App\Enums\RoleEnum;
use App\Models\Assignment;
use App\Models\AssignmentModule;
use App\Models\User;
use App\Repositories\Contracts\AssignmentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AssignmentRepository implements AssignmentRepositoryInterface
{
    public function all(User $user): Collection
    {
        $query = Assignment::with(['subject', 'modules']);

        if ($user->role === RoleEnum::STUDENT) {
            $query->whereJsonContains('classroom_ids', $user->classroom_id);
        }

        return $query->get();
    }

    public function find(int $id): ?Assignment
    {
        return Assignment::with(['subject', 'modules'])->find($id);
    }

    public function create(array $data): Assignment
    {
        return Assignment::create($data);
    }

    public function update(Assignment $assignment, array $data): Assignment
    {
        $assignment->update($data);

        return $assignment->fresh();
    }

    public function delete(Assignment $assignment): bool
    {
        return (bool) $assignment->delete();
    }

    public function createModule(int $assignmentId, array $data): AssignmentModule
    {
        return AssignmentModule::create([
            'assignment_id' => $assignmentId,
            ...$data,
        ]);
    }

    public function findModule(int $id): ?AssignmentModule
    {
        return AssignmentModule::find($id);
    }

    public function deleteModule(AssignmentModule $module): bool
    {
        return (bool) $module->delete();
    }
}
