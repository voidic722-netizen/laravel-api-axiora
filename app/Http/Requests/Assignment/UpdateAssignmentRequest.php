<?php

namespace App\Http\Requests\Assignment;

use App\Http\Requests\Concerns\DecodesJsonFields;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class UpdateAssignmentRequest extends FormRequest
{
    use DecodesJsonFields;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Source: tugas_controller.js — update
     */
    protected function prepareForValidation(): void
    {
        Log::debug('Modules raw at prepareForValidation', [
            'files' => array_map(function ($f) {
                return [
                    'name'     => $f->getClientOriginalName(),
                    'error'    => $f->getError(),
                    'is_valid' => $f->isValid(),
                    'size'     => $f->getSize(),
                ];
            }, $this->file('modules', [])),
        ]);

        $this->decodeJsonFields(['task_types', 'classroom_ids']);
    }

    public function rules(): array
    {
        return [
            'title'           => ['required', 'string', 'max:255'],
            'description'     => ['required', 'string'],
            'task_types'      => ['required', 'array'],
            'classroom_ids'   => ['required', 'array'],
            'classroom_ids.*' => ['integer'],
            'due_date'        => ['required', 'date'],
            'max_file_size'   => ['required', 'integer', 'min:1'],
            'subject_id'      => ['required', 'integer', 'exists:subjects,id'],
            'modules'         => ['nullable', 'array'],
            'modules.*'       => ['file'],
        ];
    }
}
