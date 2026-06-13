<?php

namespace App\Http\Requests\Classroom;

use Illuminate\Foundation\Http\FormRequest;

class StoreClassroomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Source: kelas_controller.js — create
     */
    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'department_id' => ['required', 'integer', 'min:1', 'exists:departments,id'],
            'semester_id'   => ['required', 'integer', 'min:1', 'exists:semesters,id'],
            'subject_id'    => ['required', 'integer', 'min:1', 'exists:subjects,id'],
        ];
    }
}
