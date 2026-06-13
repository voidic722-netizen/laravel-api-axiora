<?php

namespace App\Services;

use App\Enums\RoleEnum;
use App\Enums\SubmissionStatusEnum;
use App\Exceptions\ApiException;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\User;
use App\Repositories\Contracts\AssignmentRepositoryInterface;
use App\Repositories\Contracts\AssignmentSubmissionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

class AssignmentSubmissionService
{
    private const ALLOWED_MIME_TYPES = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/zip',
        'application/x-zip-compressed',
        'application/x-rar-compressed',
        'application/octet-stream',
        'image/jpeg',
        'image/png',
        'image/webp',
        'image/gif',
    ];

    public function __construct(
        protected AssignmentRepositoryInterface $assignmentRepository,
        protected AssignmentSubmissionRepositoryInterface $submissionRepository,
        protected CloudinaryService $cloudinaryService,
    ) {
    }

    /**
     * Source: tugas_pengumpulan_repository.js — upsertSubmission
     *
     * @param UploadedFile[] $files
     */
    public function submit(int $assignmentId, User $user, array $files): AssignmentSubmission
    {
        if ($user->role !== RoleEnum::STUDENT) {
            throw new ApiException('Forbidden', 403);
        }

        $assignment = $this->assignmentRepository->find($assignmentId);

        if (!$assignment) {
            throw new ApiException('Assignment not found', 404);
        }

        if (!$this->classroomIdInList($user->classroom_id, $assignment->classroom_ids)) {
            throw new ApiException('Assignment not found', 404);
        }

        if (empty($files)) {
            throw new ApiException('Files are required', 422);
        }

        $maxSizeBytes = $this->resolveMaxSizeBytes((int) $assignment->max_file_size);

        foreach ($files as $file) {
            if ($file->getSize() > $maxSizeBytes) {
                throw new ApiException(
                    sprintf('Maximum file size is %dMB', (int) round($maxSizeBytes / (1024 * 1024))),
                    422
                );
            }

            if (!in_array($file->getMimeType(), self::ALLOWED_MIME_TYPES, true)) {
                throw new ApiException('Unsupported file format', 422);
            }
        }

        $existing = $this->submissionRepository->findByAssignmentAndUser($assignment->id, $user->id);

        // Delete previously uploaded files from Cloudinary on resubmission (Issue #03)
        if ($existing) {
            foreach ($existing->files ?? [] as $oldFile) {
                if (!empty($oldFile['public_id'])) {
                    $this->cloudinaryService->destroy($oldFile['public_id'], 'raw');
                }
            }
        }

        $uploadedFiles = [];

        foreach ($files as $file) {
            $uploaded = $this->cloudinaryService->uploadRaw($file, "assignment-submissions/{$assignment->id}/{$user->id}");

            $uploadedFiles[] = [
                'file_name'  => $file->getClientOriginalName(),
                'file_path'  => $uploaded['url'],
                'public_id'  => $uploaded['public_id'],
                'format'     => $file->getClientOriginalExtension() ?: 'unknown',
                'file_size'  => $this->formatFileSize($file->getSize()),
            ];
        }

        $now = now();
        $status = $now->greaterThan($assignment->due_date)
            ? SubmissionStatusEnum::LATE
            : SubmissionStatusEnum::SUBMITTED;

        $payload = [
            'assignment_id' => $assignment->id,
            'user_id'       => $user->id,
            'files'         => $uploadedFiles,
            'status'        => $status,
            'submitted_at'  => $now,
        ];

        if ($existing) {
            return $this->submissionRepository->update($existing, $payload);
        }

        return $this->submissionRepository->create($payload);
    }

    /**
     * Source: tugas_pengumpulan_repository.js — getMySubmission
     */
    public function mySubmission(int $assignmentId, User $user): ?AssignmentSubmission
    {
        if ($user->role !== RoleEnum::STUDENT) {
            throw new ApiException('Forbidden', 403);
        }

        $assignment = $this->assignmentRepository->find($assignmentId);

        if (!$assignment) {
            throw new ApiException('Assignment not found', 404);
        }

        if (!$this->classroomIdInList($user->classroom_id, $assignment->classroom_ids)) {
            throw new ApiException('Assignment not found', 404);
        }

        return $this->submissionRepository->findByAssignmentAndUser($assignment->id, $user->id);
    }

    /**
     * Source: tugas_pengumpulan_repository.js — getSubmissionsByTugas
     *
     * @return Collection<int, array{
     *     student_id: int, student_name: string, classroom_id: ?int, classroom_name: string,
     *     is_submitted: bool, submission: ?AssignmentSubmission
     * }>
     */
    public function submissionsForAssignment(int $assignmentId, User $user): Collection
    {
        if (!in_array($user->role, [RoleEnum::ADMIN, RoleEnum::LECTURER], true)) {
            throw new ApiException('Forbidden', 403);
        }

        $assignment = $this->assignmentRepository->find($assignmentId);

        if (!$assignment) {
            throw new ApiException('Assignment not found', 404);
        }

        $classroomIds = array_values(array_filter(array_map('intval', $assignment->classroom_ids)));

        $students = $this->submissionRepository->studentsInClassrooms($classroomIds);
        $submissions = $this->submissionRepository->submissionsForAssignment($assignment->id);

        $submissionMap = $submissions->keyBy('user_id');

        return $students->map(function (User $student) use ($submissionMap) {
            $submission = $submissionMap->get($student->id);

            return [
                'student_id'     => $student->id,
                'student_name'   => $student->name,
                'classroom_id'   => $student->classroom_id,
                'classroom_name' => $student->classroom?->name ?? '-',
                'is_submitted'   => (bool) $submission,
                'submission'     => $submission,
            ];
        });
    }

    /**
     * Source: tugas_pengumpulan_repository.js — gradeSubmission
     */
    public function grade(int $submissionId, int $grade, ?string $feedback, User $user): AssignmentSubmission
    {
        if (!in_array($user->role, [RoleEnum::ADMIN, RoleEnum::LECTURER], true)) {
            throw new ApiException('Forbidden', 403);
        }

        $submission = $this->submissionRepository->find($submissionId);

        if (!$submission) {
            throw new ApiException('Submission not found', 404);
        }

        if ($grade < 0 || $grade > 100) {
            throw new ApiException('Grade must be between 0 and 100', 422);
        }

        return $this->submissionRepository->update($submission, [
            'grade'    => $grade,
            'feedback' => $feedback,
        ]);
    }

    /**
     * Source: tugas_pengumpulan_repository.js — getMaxSizeBytes
     * Existing data may be stored in MB (<= 1000) or bytes; normalize safely.
     */
    protected function resolveMaxSizeBytes(int $maxFileSize): int
    {
        if ($maxFileSize <= 0) {
            return 2 * 1024 * 1024;
        }

        if ($maxFileSize <= 1000) {
            return $maxFileSize * 1024 * 1024;
        }

        return $maxFileSize;
    }

    /**
     * Source: tugas_pengumpulan_repository.js — formatFileSize
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
     * Source: tugas_pengumpulan_repository.js — parseKelasIds + includes check
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
