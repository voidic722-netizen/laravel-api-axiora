<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'description'   => $this->description,
            'task_types'    => $this->task_types,
            'classroom_ids' => $this->classroom_ids,
            'due_date'      => $this->due_date,
            'max_file_size' => $this->max_file_size,
            'subject_id'    => $this->subject_id,
            'subject'       => SubjectResource::make($this->whenLoaded('subject')),
            'modules'       => AssignmentModuleResource::collection($this->whenLoaded('modules')),
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}
