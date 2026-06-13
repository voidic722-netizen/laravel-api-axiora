<?php

namespace App\Services;

use App\Enums\RoleEnum;
use App\Exceptions\ApiException;
use App\Models\Assignment;
use App\Models\User;
use App\Repositories\Contracts\AssignmentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class AssignmentService
{
    private const ALLOWED_MODULE_MIME_TYPES = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'image/jpeg', 'image/png', 'image/webp', 'image/gif',
    ];

    private const MAX_MODULE_SIZE_BYTES = 10 * 1024 * 1024; // 10MB

    public function __construct(
        protected AssignmentRepositoryInterface $assignmentRepository,
        protected CloudinaryService $cloudinaryService,
    ) {
    }

    /**
     * Source: tugas_repository.js — getTugasList
     */
    public function list(User $user): Collection
    {
        return $this->assignmentRepository->all($user);
    }

    /**
     * Source: tugas_repository.js — getTugasById
     */
    public function detail(int $id, User $user): Assignment
    {
        $assignment = $this->assignmentRepository->find($id);

        if (!$assignment) {
            throw new ApiException('Assignment not found', 404);
        }

        if ($user->role === RoleEnum::STUDENT && !$this->classroomIdInList($user->classroom_id, $assignment->classroom_ids)) {
            throw new ApiException('Assignment not found', 404);
        }

        return $assignment;
    }

    /**
     * Source: tugas_repository.js — createTugas (Issue #12: wrapped in transaction)
     *
     * @param UploadedFile[] $modules
     */
    public function create(array $data, array $modules = []): Assignment
    {
        return DB::transaction(function () use ($data, $modules) {
            $assignment = $this->assignmentRepository->create($data);

            $this->attachModules($assignment, $modules);

            return $this->assignmentRepository->find($assignment->id);
        });
    }

    /**
     * Source: tugas_repository.js — updateTugas (Issue #12: wrapped in transaction)
     *
     * @param UploadedFile[] $modules
     */
    public function update(int $id, array $data, array $modules = []): Assignment
    {
        return DB::transaction(function () use ($id, $data, $modules) {
            $assignment = $this->assignmentRepository->find($id);

            if (!$assignment) {
                throw new ApiException('Assignment not found', 404);
            }

            $this->assignmentRepository->update($assignment, $data);
            $this->attachModules($assignment, $modules);

            return $this->assignmentRepository->find($id);
        });
    }

    /**
     * Source: tugas_repository.js — deleteTugas
     */
    public function delete(int $id): void
    {
        $assignment = $this->assignmentRepository->find($id);

        if (!$assignment) {
            throw new ApiException('Assignment not found', 404);
        }

        $this->assignmentRepository->delete($assignment);
    }

    /**
     * Source: tugas_repository.js — deleteTugasModul
     * F1: cloudinary_public_id (added via additive migration) enables
     * removing the file from Cloudinary on delete.
     */
    public function deleteModule(int $moduleId): void
    {
        $module = $this->assignmentRepository->findModule($moduleId);

        if (!$module) {
            throw new ApiException('Module not found', 404);
        }

        if ($module->cloudinary_public_id) {
            $this->cloudinaryService->destroy($module->cloudinary_public_id, 'raw');
        }

        $this->assignmentRepository->deleteModule($module);
    }

    /**
     * Source: tugas_repository.js — uploadModul + formatFileSize
     *
     * @param UploadedFile[] $modules
     */
    protected function attachModules(Assignment $assignment, array $modules): void
    {
        foreach ($modules as $file) {
            if (!in_array($file->getMimeType(), self::ALLOWED_MODULE_MIME_TYPES, true)) {
                throw new ApiException('Unsupported file format', 422);
            }

            if ($file->getSize() > self::MAX_MODULE_SIZE_BYTES) {
                throw new ApiException('Maximum file size is 10MB', 422);
            }

            $uploaded = $this->cloudinaryService->uploadRaw($file, 'assignment-modules');

            $this->assignmentRepository->createModule($assignment->id, [
                'name'                  => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'file_path'             => $uploaded['url'],
                'cloudinary_public_id'  => $uploaded['public_id'],
                'format'                => $file->getClientOriginalExtension(),
                'file_size'             => $this->formatFileSize($file->getSize()),
            ]);
        }
    }

    /**
     * Source: tugas_repository.js — formatFileSize
     */
    protected function formatFileSize(int $bytes): string
    {
        if ($bytes < 1024) {
            return $bytes . ' B';
        }

        if ($bytes < 1024 * 1024) {
            return round($bytes / 1024, 1) . ' KB';
        }

        return round($bytes / (1024 * 1024), 1) . ' MB';
    }

    /**
     * Source: kelas_repository.js — normalizeIdArray + includes check
     */
    protected function classroomIdInList(?int $classroomId, array $classroomIds): bool
    {
        if ($classroomId === null) {
            return false;
        }

        $normalized = array_map('strval', $classroomIds);

        return in_array((string) $classroomId, $normalized, true);
    }
}
