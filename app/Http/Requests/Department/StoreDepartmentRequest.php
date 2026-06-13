<?php

namespace App\Http\Requests\Department;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Source: jurusan_controller.js — create/update
     * Used for both create and update (identical rule sets).
     */
    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'faculty_id'  => ['required', 'integer', 'exists:faculties,id'],
            'thumbnail'   => ['nullable', 'image', 'max:2048'],
        ];
    }
}
