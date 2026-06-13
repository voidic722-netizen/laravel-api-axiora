<?php

namespace App\Repositories;

use App\Models\Semester;
use App\Repositories\Contracts\SemesterRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SemesterRepository implements SemesterRepositoryInterface
{
    public function all(): Collection
    {
        return Semester::query()->get();
    }

    public function find(int $id): ?Semester
    {
        return Semester::find($id);
    }

    public function findDuplicate(string $name, string $academicYear, ?int $excludeId = null): ?Semester
    {
        $query = Semester::withTrashed()
            ->where('name', $name)
            ->where('academic_year', $academicYear);

        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->first();
    }

    public function create(array $data): Semester
    {
        return Semester::create($data);
    }

    public function update(Semester $semester, array $data): Semester
    {
        $semester->update($data);

        return $semester->fresh();
    }

    public function delete(Semester $semester): bool
    {
        return (bool) $semester->delete();
    }
}
