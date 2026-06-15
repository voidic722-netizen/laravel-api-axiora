<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Illuminate\Http\UploadedFile;

/**
 * Centralizes all file uploads to Cloudinary.
 * Replaces local filesystem storage from upload_helper.js (Issue #01, #02, #03).
 *
 * Uses the official cloudinary/cloudinary_php SDK directly (framework-agnostic),
 * since cloudinary-labs/cloudinary-laravel does not yet support Laravel 13.
 */
class CloudinaryService
{
    protected ?Cloudinary $cloudinary = null;

    /**
     * Lazily instantiate the Cloudinary SDK client.
     * Avoids crashing endpoints that don't perform uploads (e.g. GET /faculties)
     * when CLOUDINARY_URL is missing/invalid, since Laravel eagerly resolves
     * all constructor dependencies of any Service that injects this class.
     */
    protected function client(): Cloudinary
    {
        return $this->cloudinary ??= new Cloudinary(env('CLOUDINARY_URL'));
    }

    /**
     * Upload an image and return its secure URL.
     * Source: upload_helper.js — uploadImage
     */
    public function uploadImage(UploadedFile $file, string $folder): string
    {
        $result = $this->client()->uploadApi()->upload($file->getRealPath(), [
            'folder'        => "kiluah-lms/{$folder}",
            'resource_type' => 'image',
        ]);

        return $result['secure_url'];
    }

    /**
     * Upload a document/raw file (assignment submissions, modules).
     * Returns both the secure URL and the public_id (needed for later deletion).
     * Source: tugas_pengumpulan_repository.js — uploadSubmissionFile / tugas_repository.js — uploadModul
     *
     * @return array{url: string, public_id: string}
     */
    public function uploadRaw(UploadedFile $file, string $folder): array
    {
        $result = $this->client()->uploadApi()->upload($file->getRealPath(), [
            'folder'          => "kiluah-lms/{$folder}",
            'resource_type'   => 'raw',
            'use_filename'    => true,
            'unique_filename' => true,
        ]);

        return [
            'url'       => $result['secure_url'],
            'public_id' => $result['public_id'],
        ];
    }

    /**
     * Delete a previously uploaded file by its Cloudinary public_id.
     * Source: tugas_pengumpulan_repository.js — fs.unlinkSync(oldPath) on resubmission
     *         tugas_repository.js — deleteTugasModul
     */
    public function destroy(string $publicId, string $resourceType = 'raw'): void
    {
        $this->client()->uploadApi()->destroy($publicId, ['resource_type' => $resourceType]);
    }
}
