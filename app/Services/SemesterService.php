<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\Semester;
use App\Repositories\Contracts\SemesterRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SemesterService
{
    public function __construct(
        protected SemesterRepositoryInterface $semesterRepository,
    ) {
    }

    /**
     * Source: semester_repository.js — getSemesterList
     */
    public function list(): Collection
    {
        return $this->semesterRepository->all();
    }

    /**
     * Source: semester_repository.js — createSemester
     */
    public function create(array $data): Semester
    {
        if ($this->semesterRepository->findDuplicate($data['name'], $data['academic_year'])) {
            throw new ApiException('Semester already exists', 409);
        }

        return $this->semesterRepository->create($data);
    }

    /**
     * Source: semester_repository.js — updateSemester
     */
    public function update(int $id, array $data): Semester
    {
        $semester = $this->semesterRepository->find($id);

        if (!$semester) {
            throw new ApiException('Semester not found', 404);
        }

        if ($this->semesterRepository->findDuplicate($data['name'], $data['academic_year'], excludeId: $id)) {
            throw new ApiException('Semester already exists', 409);
        }

        return $this->semesterRepository->update($semester, $data);
    }

    /**
     * Source: semester_repository.js — deleteSemester
     */
    public function delete(int $id): void
    {
        $semester = $this->semesterRepository->find($id);

        if (!$semester) {
            throw new ApiException('Semester not found', 404);
        }

        $this->semesterRepository->delete($semester);
    }
}
