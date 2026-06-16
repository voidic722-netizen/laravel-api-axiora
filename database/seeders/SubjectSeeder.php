<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $ti = Department::where('name', 'Teknik Informatika')->first();
        $te = Department::where('name', 'Teknik Elektro')->first();

        $subjects = [
            [
                'name'          => 'Pemrograman Web',
                'type'          => 'compulsory',
                'department_id' => $ti->id,
                'description'   => 'Mata kuliah yang membahas teknologi web modern termasuk HTML, CSS, JavaScript, dan framework frontend/backend.',
                'thumbnail'     => null,
            ],
            [
                'name'          => 'Basis Data',
                'type'          => 'compulsory',
                'department_id' => $ti->id,
                'description'   => 'Mata kuliah yang membahas perancangan, implementasi, dan pengelolaan sistem basis data relasional.',
                'thumbnail'     => null,
            ],
            [
                'name'          => 'Matematika Diskrit',
                'type'          => 'general',
                'department_id' => null,
                'description'   => 'Mata kuliah umum yang membahas logika, himpunan, relasi, graf, dan kombinatorika.',
                'thumbnail'     => null,
            ],
            [
                'name'          => 'Jaringan Komputer',
                'type'          => 'compulsory',
                'department_id' => $te->id,
                'description'   => 'Mata kuliah yang membahas arsitektur jaringan, protokol komunikasi, dan keamanan jaringan.',
                'thumbnail'     => null,
            ],
        ];

        foreach ($subjects as $subject) {
            Subject::firstOrCreate(
                ['name' => $subject['name']],
                $subject,
            );
        }
    }
}
