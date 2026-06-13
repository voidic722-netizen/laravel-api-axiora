<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'type'          => $this->type->value,
            'department_id' => $this->department_id,
            'description'   => $this->description,
            'thumbnail'     => $this->thumbnail,
            'department'    => DepartmentResource::make($this->whenLoaded('department')),
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}
