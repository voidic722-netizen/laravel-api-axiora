<?php

namespace App\Repositories\Contracts;

use App\Models\Exam;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface ExamRepositoryInterface
{
    /**
     * List exams ordered by creation date (newest first). Students only see
     * exams whose classroom_ids contains their classroom.
     * Source: ujian_repository.js — getUjianList
     */
    public function all(User $user): Collection;

    /**
     * Source: ujian_repository.js — getUjianById
     */
    public function find(int $id): ?Exam;

    public function create(array $data): Exam;

    public function update(Exam $exam, array $data): Exam;

    public function delete(Exam $exam): bool;
}
