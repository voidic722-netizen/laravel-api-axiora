<?php

namespace Database\Seeders;

use App\Models\Faculty;
use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    public function run(): void
    {
        $faculties = [
            [
                'name'        => 'Fakultas Teknik',
                'description' => 'Fakultas yang menyelenggarakan pendidikan di bidang teknik dan rekayasa.',
                'thumbnail'   => null,
            ],
            [
                'name'        => 'Fakultas Ilmu Komputer',
                'description' => 'Fakultas yang berfokus pada ilmu komputer, sistem informasi, dan teknologi digital.',
                'thumbnail'   => null,
            ],
        ];

        foreach ($faculties as $faculty) {
            Faculty::firstOrCreate(['name' => $faculty['name']], $faculty);
        }
    }
}
