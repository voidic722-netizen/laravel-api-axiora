<?php

namespace App\Services;

use App\Enums\RoleEnum;
use App\Exceptions\ApiException;
use App\Models\Classroom;
use App\Models\User;
use App\Repositories\Contracts\ClassroomRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ClassroomService
{
    public function __construct(
        protected ClassroomRepositoryInterface $classroomRepository,
    ) {
    }

    /**
     * Source: kelas_repository.js — getKelasList
     */
    public function list(User $user): Collection
    {
        return $this->classroomRepository->all($user);
    }

    /**
     * Source: kelas_repository.js — getKelasDetail (Issue #16: authorization moved here)
     */
    public function detail(int $id, User $user): Classroom
    {
        $classroom = $this->classroomRepository->findDetail($id);

        if (!$classroom) {
            throw new ApiException('Classroom not found', 404);
        }

        if ($user->role === RoleEnum::STUDENT && $user->classroom_id !== $classroom->id) {
            throw new ApiException('Forbidden', 403);
        }

        if ($user->role === RoleEnum::LECTURER && $user->subject_id !== $classroom->subject_id) {
            throw new ApiException('Forbidden', 403);
        }

        $classroom->setRelation('assignments', $this->classroomRepository->assignmentsForClassroom($classroom->id));
        $classroom->setRelation('exams', $this->classroomRepository->examsForClassroom($classroom->id));

        return $classroom;
    }

    /**
     * Source: kelas_repository.js — createKelas
     */
    public function create(array $data): Classroom
    {
        $existing = $this->classroomRepository->findDuplicate(
            $data['name'],
            $data['department_id'],
            $data['semester_id'],
            $data['subject_id'],
        );

        if ($existing) {
            throw new ApiException('Classroom already exists', 409);
        }

        return $this->classroomRepository->create($data);
    }
}
