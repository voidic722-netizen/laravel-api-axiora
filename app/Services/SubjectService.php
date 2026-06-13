<?php

namespace App\Services;

use App\Enums\SubjectTypeEnum;
use App\Exceptions\ApiException;
use App\Models\Subject;
use App\Repositories\Contracts\SubjectRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

class SubjectService
{
    public function __construct(
        protected SubjectRepositoryInterface $subjectRepository,
        protected CloudinaryService $cloudinaryService,
    ) {
    }

    /**
     * Source: mata_pelajaran_repository.js — getMataPelajaranList
     */
    public function list(): Collection
    {
        return $this->subjectRepository->all();
    }

    /**
     * Source: mata_pelajaran_repository.js — getMataPelajaranDetail
     */
    public function detail(int $id): Subject
    {
        $subject = $this->subjectRepository->find($id);

        if (!$subject) {
            throw new ApiException('Subject not found', 404);
        }

        return $subject;
    }

    /**
     * Source: mata_pelajaran_repository.js — createMataPelajaran
     */
    public function create(array $data, ?UploadedFile $thumbnail = null): Subject
    {
        $this->assertCompulsoryHasDepartment($data);

        $data['department_id'] = $data['department_id'] ?? null;

        if ($thumbnail) {
            $data['thumbnail'] = $this->cloudinaryService->uploadImage($thumbnail, 'subjects');
        }

        return $this->subjectRepository->create($data);
    }

    /**
     * Source: mata_pelajaran_repository.js — updateMataPelajaran
     */
    public function update(int $id, array $data, ?UploadedFile $thumbnail = null): Subject
    {
        $subject = $this->subjectRepository->find($id);

        if (!$subject) {
            throw new ApiException('Subject not found', 404);
        }

        $this->assertCompulsoryHasDepartment($data);

        $data['department_id'] = $data['department_id'] ?? null;

        if ($thumbnail) {
            $data['thumbnail'] = $this->cloudinaryService->uploadImage($thumbnail, 'subjects');
        }

        return $this->subjectRepository->update($subject, $data);
    }

    /**
     * Source: mata_pelajaran_repository.js — deleteMataPelajaran
     */
    public function delete(int $id): void
    {
        $subject = $this->subjectRepository->find($id);

        if (!$subject) {
            throw new ApiException('Subject not found', 404);
        }

        $this->subjectRepository->delete($subject);
    }

    /**
     * Source: mata_pelajaran_controller.js —
     *   if (type === 'wajib' && !jurusan_id) → 403 'Jurusan is required for mata pelajaran wajib'
     */
    protected function assertCompulsoryHasDepartment(array $data): void
    {
        if ($data['type'] === SubjectTypeEnum::COMPULSORY->value && empty($data['department_id'])) {
            throw new ApiException('Department is required for compulsory subjects', 422);
        }
    }
}
