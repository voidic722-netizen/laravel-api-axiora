<?php

namespace App\Repositories;

use App\Enums\RoleEnum;
use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\Exam;
use App\Models\User;
use App\Repositories\Contracts\ClassroomRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ClassroomRepository implements ClassroomRepositoryInterface
{
    public function all(User $user): Collection
    {
        $query = Classroom::with(['department', 'semester', 'subject']);

        if ($user->role === RoleEnum::STUDENT) {
            $query->where('id', $user->classroom_id);
        }

        return $query->get();
    }

    public function findDetail(int $id): ?Classroom
    {
        $classroom = Classroom::with(['department.faculty', 'semester', 'subject'])->find($id);

        if (!$classroom) {
            return null;
        }

        $classroom->setRelation('lecturers', $classroom->lecturers()->orderBy('name')->get());
        $classroom->setRelation('students', $classroom->students()->orderBy('name')->get());
        $classroom->setRelation('schedules', $classroom->schedules()->orderBy('date')->get());

        return $classroom;
    }

    public function find(int $id): ?Classroom
    {
        return Classroom::find($id);
    }

    public function findDuplicate(string $name, int $departmentId, int $semesterId, int $subjectId): ?Classroom
    {
        return Classroom::withTrashed()
            ->where('name', $name)
            ->where('department_id', $departmentId)
            ->where('semester_id', $semesterId)
            ->where('subject_id', $subjectId)
            ->first();
    }

    public function create(array $data): Classroom
    {
        return Classroom::create($data);
    }

    public function update(Classroom $classroom, array $data): Classroom
    {
        $classroom->update($data);

        return $classroom->fresh();
    }

    public function delete(Classroom $classroom): bool
    {
        return (bool) $classroom->delete();
    }

    public function assignmentsForClassroom(int $classroomId): Collection
    {
        return Assignment::with(['subject', 'modules'])
            ->whereJsonContains('classroom_ids', $classroomId)
            ->orderBy('due_date')
            ->get();
    }

    public function examsForClassroom(int $classroomId): Collection
    {
        return Exam::whereJsonContains('classroom_ids', $classroomId)
            ->orderBy('deadline_at')
            ->get();
    }
}
