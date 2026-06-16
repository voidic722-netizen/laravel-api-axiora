<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Department;
use App\Models\Semester;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class ClassroomSeeder extends Seeder
{
    public function run(): void
    {
        $ti       = Department::where('name', 'Teknik Informatika')->first();
        $te       = Department::where('name', 'Teknik Elektro')->first();
        $ganjil   = Semester::where('name', 'Ganjil')->where('academic_year', '2025/2026')->first();
        $web      = Subject::where('name', 'Pemrograman Web')->first();
        $db       = Subject::where('name', 'Basis Data')->first();
        $jaringan = Subject::where('name', 'Jaringan Komputer')->first();

        $classrooms = [
            [
                'name'          => 'TI-2A',
                'department_id' => $ti->id,
                'semester_id'   => $ganjil->id,
                'subject_id'    => $web->id,
            ],
            [
                'name'          => 'TI-2B',
                'department_id' => $ti->id,
                'semester_id'   => $ganjil->id,
                'subject_id'    => $db->id,
            ],
            [
                'name'          => 'TE-3A',
                'department_id' => $te->id,
                'semester_id'   => $ganjil->id,
                'subject_id'    => $jaringan->id,
            ],
        ];

        foreach ($classrooms as $classroom) {
            Classroom::firstOrCreate(
                [
                    'name'          => $classroom['name'],
                    'department_id' => $classroom['department_id'],
                    'semester_id'   => $classroom['semester_id'],
                    'subject_id'    => $classroom['subject_id'],
                ],
                $classroom,
            );
        }
    }
}
