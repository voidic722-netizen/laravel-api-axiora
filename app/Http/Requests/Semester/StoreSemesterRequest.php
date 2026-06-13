<?php

namespace App\Http\Requests\Semester;

use Illuminate\Foundation\Http\FormRequest;

class StoreSemesterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Source: semester_controller.js — create/update
     * Used for both create and update (identical rule sets).
     */
    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'academic_year' => ['required', 'string', 'max:255'],
            'start_date'    => ['required', 'date'],
            'end_date'      => ['required', 'date', 'after_or_equal:start_date'],
        ];
    }
}
