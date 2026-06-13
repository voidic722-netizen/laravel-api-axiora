<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'email'         => $this->email,
            'image'         => $this->image,
            'role'          => $this->role->value,
            'position'      => $this->position,
            'nidn'          => $this->nidn,
            'nim'           => $this->nim,
            'subject_id'    => $this->subject_id,
            'department_id' => $this->department_id,
            'faculty_id'    => $this->faculty_id,
            'classroom_id'  => $this->classroom_id,
            'subject'       => SubjectResource::make($this->whenLoaded('subject')),
            'department'    => DepartmentResource::make($this->whenLoaded('department')),
            'faculty'       => FacultyResource::make($this->whenLoaded('faculty')),
            'classroom'     => ClassroomResource::make($this->whenLoaded('classroom')),
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}
