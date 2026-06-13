<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Full exam representation, including correct answers (questions[*].options[*].isCorrect).
 * For students, use ExamStudentResource instead (Issue #17).
 */
class ExamResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'title'             => $this->title,
            'description'       => $this->description,
            'exam_categories'   => $this->exam_categories,
            'classroom_ids'     => $this->classroom_ids,
            'available_at'      => $this->available_at,
            'deadline_at'       => $this->deadline_at,
            'duration_minutes'  => $this->duration_minutes,
            'questions'         => $this->questions,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }
}
