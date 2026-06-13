<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamSubmissionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'exam_id'          => $this->exam_id,
            'user_id'          => $this->user_id,
            'answers'          => $this->answers,
            'score'            => $this->score,
            'correct_count'    => $this->correct_count,
            'total_questions'  => $this->total_questions,
            'submitted_at'     => $this->submitted_at,
            'student'          => UserResource::make($this->whenLoaded('student')),
            'created_at'       => $this->created_at,
            'updated_at'       => $this->updated_at,
        ];
    }
}
