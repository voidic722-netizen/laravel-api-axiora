<?php

namespace App\Repositories;

use App\Enums\PositionEnum;
use App\Enums\RoleEnum;
use App\Models\Faculty;
use App\Models\User;
use App\Repositories\Contracts\FacultyRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class FacultyRepository implements FacultyRepositoryInterface
{
    public function all(): Collection
    {
        return Faculty::query()->get();
    }

    public function availableForDean(?int $excludeUserId = null): Collection
    {
        $occupiedQuery = User::where('position', PositionEnum::DEAN)
            ->whereNotNull('faculty_id');

        if ($excludeUserId !== null) {
            $occupiedQuery->where('id', '!=', $excludeUserId);
        }

        $occupiedFacultyIds = $occupiedQuery->pluck('faculty_id');

        return Faculty::whereNotIn('id', $occupiedFacultyIds)->get();
    }

    public function findDetail(int $id): ?Faculty
    {
        $faculty = Faculty::with('departments')->find($id);

        if (!$faculty) {
            return null;
        }

        $faculty->setRelation('dean', $faculty->dean()->first());

        $faculty->setRelation(
            'lecturers',
            $faculty->lecturers()->with('department')->get()
        );

        $faculty->setRelation(
            'students',
            $faculty->students()->with(['department', 'classroom'])->get()
        );

        return $faculty;
    }

    public function find(int $id): ?Faculty
    {
        return Faculty::find($id);
    }

    public function findByName(string $name, ?int $excludeId = null): ?Faculty
    {
        $query = Faculty::withTrashed()->where('name', $name);

        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->first();
    }

    public function create(array $data): Faculty
    {
        return Faculty::create($data);
    }

    public function update(Faculty $faculty, array $data): Faculty
    {
        $faculty->update($data);

        return $faculty->fresh();
    }

    public function delete(Faculty $faculty): bool
    {
        return (bool) $faculty->delete();
    }
}
