<?php

namespace App\Http\Requests\Exam;

use App\Http\Requests\Concerns\DecodesJsonFields;
use Illuminate\Foundation\Http\FormRequest;

class StoreExamRequest extends FormRequest
{
    use DecodesJsonFields;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Source: ujian_controller.js — create
     * exam_categories, classroom_ids, and questions arrive as JSON strings
     * in multipart requests (file uploads for question images).
     */
    protected function prepareForValidation(): void
    {
        $this->decodeJsonFields(['exam_categories', 'classroom_ids', 'questions']);
    }

    public function rules(): array
    {
        return [
            'title'             => ['required', 'string', 'max:255'],
            'description'       => ['nullable', 'string'],
            'exam_categories'   => ['required', 'array'],
            'classroom_ids'     => ['required', 'array'],
            'classroom_ids.*'   => ['integer'],
            'available_at'      => ['required', 'date'],
            'deadline_at'       => ['required', 'date'],
            'duration_minutes'  => ['required', 'integer', 'min:1'],
            'questions'         => ['required', 'array', 'min:1'],
            'questions.*.id'    => ['required'],
            'questions.*.options' => ['required', 'array'],
        ];
    }
}
