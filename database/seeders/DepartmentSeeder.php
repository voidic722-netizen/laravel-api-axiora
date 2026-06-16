<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Faculty;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $teknik      = Faculty::where('name', 'Fakultas Teknik')->first();
        $ilmuKomputer = Faculty::where('name', 'Fakultas Ilmu Komputer')->first();

        $departments = [
            [
                'name'        => 'Teknik Informatika',
                'description' => 'Program studi yang mempelajari algoritma, pemrograman, dan rekayasa perangkat lunak.',
                'faculty_id'  => $teknik->id,
                'thumbnail'   => null,
            ],
            [
                'name'        => 'Teknik Elektro',
                'description' => 'Program studi yang mempelajari sistem kelistrikan, elektronika, dan kontrol.',
                'faculty_id'  => $teknik->id,
                'thumbnail'   => null,
            ],
            [
                'name'        => 'Sistem Informasi',
                'description' => 'Program studi yang mengintegrasikan teknologi informasi dengan kebutuhan bisnis.',
                'faculty_id'  => $ilmuKomputer->id,
                'thumbnail'   => null,
            ],
            [
                'name'        => 'Ilmu Komputer',
                'description' => 'Program studi yang berfokus pada dasar-dasar komputasi dan kecerdasan buatan.',
                'faculty_id'  => $ilmuKomputer->id,
                'thumbnail'   => null,
            ],
        ];

        foreach ($departments as $department) {
            Department::firstOrCreate(
                ['name' => $department['name']],
                $department,
            );
        }
    }
}
