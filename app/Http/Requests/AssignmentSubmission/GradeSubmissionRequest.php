<?php

namespace App\Http\Requests\AssignmentSubmission;

use Illuminate\Foundation\Http\FormRequest;

class GradeSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Source: tugas_pengumpulan_controller.js — grade
     */
    public function rules(): array
    {
        return [
            'grade'    => ['required', 'integer', 'min:0', 'max:100'],
            'feedback' => ['nullable', 'string'],
        ];
    }
}
