<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentModuleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'assignment_id'         => $this->assignment_id,
            'name'                  => $this->name,
            'file_path'             => $this->file_path,
            'cloudinary_public_id'  => $this->cloudinary_public_id,
            'format'                => $this->format,
            'file_size'             => $this->file_size,
            'created_at'            => $this->created_at,
            'updated_at'            => $this->updated_at,
        ];
    }
}
