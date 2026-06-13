<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassroomResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'department_id' => $this->department_id,
            'semester_id'   => $this->semester_id,
            'subject_id'    => $this->subject_id,
            'department'    => DepartmentResource::make($this->whenLoaded('department')),
            'semester'      => SemesterResource::make($this->whenLoaded('semester')),
            'subject'       => SubjectResource::make($this->whenLoaded('subject')),
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}
