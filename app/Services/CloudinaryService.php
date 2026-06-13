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
    protected Cloudinary $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary(env('CLOUDINARY_URL'));
    }

    /**
     * Upload an image and return its secure URL.
     * Source: upload_helper.js — uploadImage
     */
    public function uploadImage(UploadedFile $file, string $folder): string
    {
        $result = $this->cloudinary->uploadApi()->upload($file->getRealPath(), [
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
        $result = $this->cloudinary->uploadApi()->upload($file->getRealPath(), [
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
        $this->cloudinary->uploadApi()->destroy($publicId, ['resource_type' => $resourceType]);
    }
}
