<?php

namespace App\Repositories\Contracts;

use App\Models\Faculty;
use Illuminate\Database\Eloquent\Collection;

interface FacultyRepositoryInterface
{
    /**
     * Source: fakultas_repository.js — getFakultasList
     */
    public function all(): Collection;

    /**
     * Faculties that do NOT currently have a dean assigned.
     * Source: fakultas_repository.js — getAvailableFakultasForDekan
     */
    public function availableForDean(?int $excludeUserId = null): Collection;

    /**
     * Faculty detail with departments, dean, lecturers, and students.
     * Source: fakultas_repository.js — getFakultasDetail
     */
    public function findDetail(int $id): ?Faculty;

    public function find(int $id): ?Faculty;

    /**
     * Find a faculty by name (including soft-deleted), optionally excluding an id.
     * Source: fakultas_repository.js — createFakultas/updateFakultas duplicate check
     */
    public function findByName(string $name, ?int $excludeId = null): ?Faculty;

    public function create(array $data): Faculty;

    public function update(Faculty $faculty, array $data): Faculty;

    public function delete(Faculty $faculty): bool;
}
