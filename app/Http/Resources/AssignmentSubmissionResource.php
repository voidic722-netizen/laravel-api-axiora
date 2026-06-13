<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentSubmissionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'assignment_id' => $this->assignment_id,
            'user_id'       => $this->user_id,
            'files'         => $this->files,
            'status'        => $this->status->value,
            'submitted_at'  => $this->submitted_at,
            'grade'         => $this->grade,
            'feedback'      => $this->feedback,
            'student'       => UserResource::make($this->whenLoaded('student')),
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}
