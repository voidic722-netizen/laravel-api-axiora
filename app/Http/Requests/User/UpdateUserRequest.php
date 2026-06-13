<?php

namespace App\Http\Requests\User;

use App\Enums\RoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Source: user_controller.js — update
     * Password is optional on update (only changed if provided).
     */
    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:255'],
            'password'      => ['nullable', 'string', 'min:6'],
            'role'          => ['required', Rule::in([
                RoleEnum::ADMIN->value,
                RoleEnum::LECTURER->value,
                RoleEnum::STUDENT->value,
            ])],
            'position'      => ['nullable', 'string'],
            'nidn'          => ['nullable', 'string'],
            'nim'           => ['nullable', 'string'],
            'subject_id'    => ['nullable', 'integer', 'exists:subjects,id'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'faculty_id'    => ['nullable', 'integer', 'exists:faculties,id'],
            'classroom_id'  => ['nullable', 'integer', 'exists:classrooms,id'],
            'image'         => ['nullable', 'image', 'max:2048'],
        ];
    }
}
