<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Centralizes all file uploads to local storage (public disk).
 *
 * NOTE: Class name and method signatures are intentionally kept identical
 * to the original Cloudinary-based implementation so that no consumer
 * (AssignmentService, AssignmentSubmissionService, UserService,
 * DepartmentService, ExamService, FacultyService, SubjectService)
 * needs to be modified. Only the internal storage mechanism changed.
 *
 * Files are stored under storage/app/public/axiora-lms/{folder}/ and
 * served via the public disk symlink (php artisan storage:link).
 */
class CloudinaryService
{
    /**
     * Upload an image and return its public URL.
     * Source: upload_helper.js — uploadImage
     */
    public function uploadImage(UploadedFile $file, string $folder): string
    {
        $path = $this->storeFile($file, $folder);

        return Storage::disk('public')->url($path);
    }

    /**
     * Upload a document/raw file (assignment submissions, modules).
     * Returns both the public URL and the relative path (used as "public_id"
     * for later deletion, mirroring the original Cloudinary contract).
     * Source: tugas_pengumpulan_repository.js — uploadSubmissionFile / tugas_repository.js — uploadModul
     *
     * @return array{url: string, public_id: string}
     */
    public function uploadRaw(UploadedFile $file, string $folder): array
    {
        $path = $this->storeFile($file, $folder);

        return [
            'url'       => Storage::disk('public')->url($path),
            'public_id' => $path,
        ];
    }

    /**
     * Delete a previously uploaded file by its stored relative path.
     * $resourceType is kept for backward signature compatibility but is
     * unused for local storage (no distinction needed between image/raw).
     * Source: tugas_pengumpulan_repository.js — fs.unlinkSync(oldPath) on resubmission
     *         tugas_repository.js — deleteTugasModul
     */
    public function destroy(string $publicId, string $resourceType = 'raw'): void
    {
        $storageUrl = Storage::disk('public')->url('');
        
        if (str_starts_with($publicId, $storageUrl)) {
            $publicId = substr($publicId, strlen($storageUrl));
        }

        if (Storage::disk('public')->exists($publicId)) {
            Storage::disk('public')->delete($publicId);
        }
    }

    /**
     * Store the uploaded file under axiora-lms/{folder} on the public disk
     * with a unique generated filename (mirrors Cloudinary's
     * unique_filename behavior).
     */
    protected function storeFile(UploadedFile $file, string $folder): string
    {
        $filename = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();

        return $file->storeAs("axiora-lms/{$folder}", $filename, 'public');
    }
}
