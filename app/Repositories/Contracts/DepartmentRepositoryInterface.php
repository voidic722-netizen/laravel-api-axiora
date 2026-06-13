<?php

namespace App\Repositories\Contracts;

use App\Models\Department;
use Illuminate\Database\Eloquent\Collection;

interface DepartmentRepositoryInterface
{
    /**
     * Source: jurusan_repository.js — getJurusanList
     */
    public function all(): Collection;

    /**
     * Department detail with faculty, lecturers, students, subjects, classrooms.
     * Source: jurusan_repository.js — getJurusanDetail
     */
    public function findDetail(int $id): ?Department;

    public function find(int $id): ?Department;

    /**
     * Find a department by name (including soft-deleted), optionally excluding an id.
     * Source: jurusan_repository.js — createJurusan/updateJurusan duplicate check
     */
    public function findByName(string $name, ?int $excludeId = null): ?Department;

    public function create(array $data): Department;

    public function update(Department $department, array $data): Department;

    public function delete(Department $department): bool;
}
