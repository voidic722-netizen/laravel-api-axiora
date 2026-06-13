<?php

namespace App\Http\Resources;

use App\Enums\RoleEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassroomDetailResource extends JsonResource
{
    /**
     * Source: kelas_repository.js — getKelasDetail
     */
    public function toArray(Request $request): array
    {
        $lecturers = $this->whenLoaded('lecturers');

        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'department_id' => $this->department_id,
            'semester_id'   => $this->semester_id,
            'subject_id'    => $this->subject_id,
            'department'    => DepartmentResource::make($this->whenLoaded('department')),
            'semester'      => SemesterResource::make($this->whenLoaded('semester')),
            'subject'       => SubjectResource::make($this->whenLoaded('subject')),
            'lecturers'     => UserResource::collection($lecturers),
            'teacher'       => $lecturers instanceof \Illuminate\Support\Collection
                ? UserResource::make($lecturers->first())
                : null,
            'students'      => UserResource::collection($this->whenLoaded('students')),
            'student_count' => $this->whenLoaded('students', fn () => $this->students->count()),
            'schedules'     => ScheduleResource::collection($this->whenLoaded('schedules')),
            'assignments'   => AssignmentResource::collection($this->whenLoaded('assignments')),
            'exams'         => $this->whenLoaded('exams', function () use ($request) {
                // H1 fix: students must not see exam answer keys via classroom detail.
                $isStudent = $request->user()?->role === RoleEnum::STUDENT;

                return $isStudent
                    ? ExamStudentResource::collection($this->exams)
                    : ExamResource::collection($this->exams);
            }),
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}
