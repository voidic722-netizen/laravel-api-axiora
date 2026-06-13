<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\Department;
use App\Repositories\Contracts\DepartmentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

class DepartmentService
{
    public function __construct(
        protected DepartmentRepositoryInterface $departmentRepository,
        protected CloudinaryService $cloudinaryService,
    ) {
    }

    /**
     * Source: jurusan_repository.js — getJurusanList
     */
    public function list(): Collection
    {
        return $this->departmentRepository->all();
    }

    /**
     * Source: jurusan_repository.js — getJurusanDetail
     */
    public function detail(int $id): Department
    {
        $department = $this->departmentRepository->findDetail($id);

        if (!$department) {
            throw new ApiException('Department not found', 404);
        }

        return $department;
    }

    /**
     * Source: jurusan_repository.js — createJurusan
     */
    public function create(array $data, ?UploadedFile $thumbnail = null): Department
    {
        if ($this->departmentRepository->findByName($data['name'])) {
            throw new ApiException('Department already exists', 409);
        }

        if ($thumbnail) {
            $data['thumbnail'] = $this->cloudinaryService->uploadImage($thumbnail, 'departments');
        }

        return $this->departmentRepository->create($data);
    }

    /**
     * Source: jurusan_repository.js — updateJurusan
     */
    public function update(int $id, array $data, ?UploadedFile $thumbnail = null): Department
    {
        $department = $this->departmentRepository->find($id);

        if (!$department) {
            throw new ApiException('Department not found', 404);
        }

        if ($this->departmentRepository->findByName($data['name'], excludeId: $id)) {
            throw new ApiException('Department already exists', 409);
        }

        if ($thumbnail) {
            $data['thumbnail'] = $this->cloudinaryService->uploadImage($thumbnail, 'departments');
        }

        return $this->departmentRepository->update($department, $data);
    }

    /**
     * Source: jurusan_repository.js — deleteJurusan
     */
    public function delete(int $id): void
    {
        $department = $this->departmentRepository->find($id);

        if (!$department) {
            throw new ApiException('Department not found', 404);
        }

        $this->departmentRepository->delete($department);
    }
}
