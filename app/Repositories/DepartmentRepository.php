<?php

namespace App\Repositories;

use App\Models\Department;
use App\Repositories\Contracts\DepartmentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class DepartmentRepository implements DepartmentRepositoryInterface
{
    public function all(): Collection
    {
        return Department::query()->get();
    }

    public function findDetail(int $id): ?Department
    {
        $department = Department::with('faculty')->find($id);

        if (!$department) {
            return null;
        }

        $department->setRelation(
            'lecturers',
            $department->lecturers()->get()
        );

        $department->setRelation(
            'students',
            $department->students()->with('classroom')->get()
        );

        $department->setRelation(
            'subjects',
            $department->subjects()->with('lecturers')->get()
        );

        $department->setRelation(
            'classrooms',
            $department->classrooms()->with(['semester', 'subject'])->get()
        );

        return $department;
    }

    public function find(int $id): ?Department
    {
        return Department::find($id);
    }

    public function findByName(string $name, ?int $excludeId = null): ?Department
    {
        $query = Department::withTrashed()->where('name', $name);

        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->first();
    }

    public function create(array $data): Department
    {
        return Department::create($data);
    }

    public function update(Department $department, array $data): Department
    {
        $department->update($data);

        return $department->fresh();
    }

    public function delete(Department $department): bool
    {
        return (bool) $department->delete();
    }
}
