<?php

namespace App\Http\Requests\AssignmentSubmission;

use Illuminate\Foundation\Http\FormRequest;

class SubmitAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Source: tugas_pengumpulan_controller.js — submit
     * The "Files are required" check is enforced in
     * AssignmentSubmissionService::submit (depends on the assignment's
     * max_file_size for per-file size validation).
     */
    public function rules(): array
    {
        return [
            'files'   => ['required', 'array', 'min:1'],
            'files.*' => ['file'],
        ];
    }
}
