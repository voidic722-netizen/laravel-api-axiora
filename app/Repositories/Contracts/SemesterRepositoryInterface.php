<?php

namespace App\Repositories\Contracts;

use App\Models\Semester;
use Illuminate\Database\Eloquent\Collection;

interface SemesterRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): ?Semester;

    /**
     * Find a semester by name + academic_year (including soft-deleted), optionally excluding an id.
     * Source: semester_repository.js — createSemester/updateSemester duplicate check
     */
    public function findDuplicate(string $name, string $academicYear, ?int $excludeId = null): ?Semester;

    public function create(array $data): Semester;

    public function update(Semester $semester, array $data): Semester;

    public function delete(Semester $semester): bool;
}
