<?php

namespace App\Repositories\Contracts;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Collection;

interface SubjectRepositoryInterface
{
    /**
     * Source: mata_pelajaran_repository.js — getMataPelajaranList
     */
    public function all(): Collection;

    public function find(int $id): ?Subject;

    public function create(array $data): Subject;

    public function update(Subject $subject, array $data): Subject;

    public function delete(Subject $subject): bool;
}
