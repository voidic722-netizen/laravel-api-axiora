<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Student-facing exam representation with correct answers stripped from
 * every question's options (Issue #17).
 * Source: ujian_repository.js — stripCorrectAnswers
 */
class ExamStudentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'title'            => $this->title,
            'description'      => $this->description,
            'exam_categories'  => $this->exam_categories,
            'classroom_ids'    => $this->classroom_ids,
            'available_at'     => $this->available_at,
            'deadline_at'      => $this->deadline_at,
            'duration_minutes' => $this->duration_minutes,
            'questions'        => $this->stripCorrectAnswers($this->questions ?? []),
            'created_at'       => $this->created_at,
            'updated_at'       => $this->updated_at,
        ];
    }

    /**
     * @param array<int, array> $questions
     * @return array<int, array>
     */
    protected function stripCorrectAnswers(array $questions): array
    {
        return array_map(function (array $question) {
            $question['options'] = array_map(function (array $option) {
                unset($option['isCorrect']);

                return $option;
            }, $question['options'] ?? []);

            return $question;
        }, $questions);
    }
}
