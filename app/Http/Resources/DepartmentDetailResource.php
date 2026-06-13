<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentDetailResource extends JsonResource
{
    /**
     * Source: jurusan_repository.js — getJurusanDetail
     * Each subject is annotated with its assigned lecturer's name
     * (subject.lecturers is eager-loaded by DepartmentRepository::findDetail).
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'faculty_id'  => $this->faculty_id,
            'thumbnail'   => $this->thumbnail,
            'faculty'     => FacultyResource::make($this->whenLoaded('faculty')),
            'lecturers'   => UserResource::collection($this->whenLoaded('lecturers')),
            'students'    => UserResource::collection($this->whenLoaded('students')),
            'subjects'    => $this->whenLoaded('subjects', function () {
                return $this->subjects->map(function ($subject) {
                    return [
                        ...SubjectResource::make($subject)->resolve(),
                        'teacher' => $subject->lecturers->first()->name ?? null,
                    ];
                });
            }),
            'classrooms'  => ClassroomResource::collection($this->whenLoaded('classrooms')),
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}
