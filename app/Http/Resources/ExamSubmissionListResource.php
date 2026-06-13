<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Wraps the per-student submission status array returned by
 * ExamSubmissionService::submissions().
 * Source: ujian_repository.js — getSubmissions
 */
class ExamSubmissionListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $row = $this->resource;

        return [
            'student_id'     => $row['student_id'],
            'student_name'   => $row['student_name'],
            'student_nim'    => $row['student_nim'],
            'student_email'  => $row['student_email'],
            'classroom_id'   => $row['classroom_id'],
            'classroom_name' => $row['classroom_name'],
            'is_submitted'   => $row['is_submitted'],
            'submission'     => $row['submission']
                ? ExamSubmissionResource::make($row['submission'])
                : null,
        ];
    }
}
