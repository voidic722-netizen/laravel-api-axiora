<?php

namespace App\Repositories\Contracts;

use App\Models\Assignment;
use App\Models\AssignmentModule;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface AssignmentRepositoryInterface
{
    /**
     * List assignments with subject and modules. Students only see
     * assignments whose classroom_ids contains their classroom.
     * Source: tugas_repository.js — getTugasList
     */
    public function all(User $user): Collection;

    /**
     * Find an assignment with subject and modules eager-loaded.
     * Source: tugas_repository.js — getTugasById
     */
    public function find(int $id): ?Assignment;

    public function create(array $data): Assignment;

    public function update(Assignment $assignment, array $data): Assignment;

    public function delete(Assignment $assignment): bool;

    /**
     * Source: tugas_repository.js — createTugas/updateTugas (TugasModul.create)
     */
    public function createModule(int $assignmentId, array $data): AssignmentModule;

    public function findModule(int $id): ?AssignmentModule;

    /**
     * Source: tugas_repository.js — deleteTugasModul
     */
    public function deleteModule(AssignmentModule $module): bool;
}
