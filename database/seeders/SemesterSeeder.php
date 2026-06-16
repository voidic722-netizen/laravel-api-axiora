<?php

namespace Database\Seeders;

use App\Models\Semester;
use Illuminate\Database\Seeder;

class SemesterSeeder extends Seeder
{
    public function run(): void
    {
        $semesters = [
            [
                'name'          => 'Ganjil',
                'academic_year' => '2025/2026',
                'start_date'    => '2025-09-01',
                'end_date'      => '2026-01-31',
            ],
            [
                'name'          => 'Genap',
                'academic_year' => '2025/2026',
                'start_date'    => '2026-02-01',
                'end_date'      => '2026-06-30',
            ],
        ];

        foreach ($semesters as $semester) {
            Semester::firstOrCreate(
                [
                    'name'          => $semester['name'],
                    'academic_year' => $semester['academic_year'],
                ],
                $semester,
            );
        }
    }
}
