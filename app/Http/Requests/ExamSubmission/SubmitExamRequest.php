<?php

namespace App\Http\Requests\ExamSubmission;

use App\Http\Requests\Concerns\DecodesJsonFields;
use Illuminate\Foundation\Http\FormRequest;

class SubmitExamRequest extends FormRequest
{
    use DecodesJsonFields;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Source: ujian_pengumpulan_controller.js — submit
     * `answers` may arrive as a JSON string or a native array/object.
     */
    protected function prepareForValidation(): void
    {
        $this->decodeJsonFields(['answers']);
    }

    public function rules(): array
    {
        return [
            'answers' => ['required', 'array'],
        ];
    }
}
