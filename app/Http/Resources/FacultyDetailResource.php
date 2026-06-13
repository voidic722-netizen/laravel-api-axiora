<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FacultyDetailResource extends JsonResource
{
    /**
     * Source: fakultas_repository.js — getFakultasDetail
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'thumbnail'   => $this->thumbnail,
            'departments' => DepartmentResource::collection($this->whenLoaded('departments')),
            'dean'        => UserResource::make($this->whenLoaded('dean')),
            'lecturers'   => UserResource::collection($this->whenLoaded('lecturers')),
            'students'    => UserResource::collection($this->whenLoaded('students')),
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}
