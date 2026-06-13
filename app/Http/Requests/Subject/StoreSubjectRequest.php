<?php

namespace App\Http\Requests\Subject;

use App\Enums\SubjectTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Source: mata_pelajaran_controller.js — create/update
     * The cross-field rule (compulsory requires department_id) is enforced
     * in SubjectService::assertCompulsoryHasDepartment.
     */
    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'type'          => ['required', Rule::in([
                SubjectTypeEnum::GENERAL->value,
                SubjectTypeEnum::COMPULSORY->value,
            ])],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'description'   => ['required', 'string'],
            'thumbnail'     => ['nullable', 'image', 'max:2048'],
        ];
    }
}
