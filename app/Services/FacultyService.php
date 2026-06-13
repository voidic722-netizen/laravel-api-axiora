<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\Faculty;
use App\Repositories\Contracts\FacultyRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

class FacultyService
{
    public function __construct(
        protected FacultyRepositoryInterface $facultyRepository,
        protected CloudinaryService $cloudinaryService,
    ) {
    }

    /**
     * Source: fakultas_repository.js — getFakultasList
     */
    public function list(): Collection
    {
        return $this->facultyRepository->all();
    }

    /**
     * Source: fakultas_repository.js — getAvailableFakultasForDekan
     */
    public function availableForDean(?int $excludeUserId = null): Collection
    {
        return $this->facultyRepository->availableForDean($excludeUserId);
    }

    /**
     * Source: fakultas_repository.js — getFakultasDetail
     */
    public function detail(int $id): Faculty
    {
        $faculty = $this->facultyRepository->findDetail($id);

        if (!$faculty) {
            throw new ApiException('Faculty not found', 404);
        }

        return $faculty;
    }

    /**
     * Source: fakultas_repository.js — createFakultas
     */
    public function create(array $data, ?UploadedFile $thumbnail = null): Faculty
    {
        if ($this->facultyRepository->findByName($data['name'])) {
            throw new ApiException('Faculty already exists', 409);
        }

        if ($thumbnail) {
            $data['thumbnail'] = $this->cloudinaryService->uploadImage($thumbnail, 'faculties');
        }

        return $this->facultyRepository->create($data);
    }

    /**
     * Source: fakultas_repository.js — updateFakultas
     */
    public function update(int $id, array $data, ?UploadedFile $thumbnail = null): Faculty
    {
        $faculty = $this->facultyRepository->find($id);

        if (!$faculty) {
            throw new ApiException('Faculty not found', 404);
        }

        if ($this->facultyRepository->findByName($data['name'], excludeId: $id)) {
            throw new ApiException('Faculty already exists', 409);
        }

        if ($thumbnail) {
            $data['thumbnail'] = $this->cloudinaryService->uploadImage($thumbnail, 'faculties');
        }

        return $this->facultyRepository->update($faculty, $data);
    }

    /**
     * Source: fakultas_repository.js — deleteFakultas
     */
    public function delete(int $id): void
    {
        $faculty = $this->facultyRepository->find($id);

        if (!$faculty) {
            throw new ApiException('Faculty not found', 404);
        }

        $this->facultyRepository->delete($faculty);
    }
}
