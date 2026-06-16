<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Classroom;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $fteknik  = Faculty::where('name', 'Fakultas Teknik')->first();
        $fikomputer = Faculty::where('name', 'Fakultas Ilmu Komputer')->first();
        $ti       = Department::where('name', 'Teknik Informatika')->first();
        $te       = Department::where('name', 'Teknik Elektro')->first();
        $si       = Department::where('name', 'Sistem Informasi')->first();
        $web      = Subject::where('name', 'Pemrograman Web')->first();
        $db       = Subject::where('name', 'Basis Data')->first();
        $jaringan = Subject::where('name', 'Jaringan Komputer')->first();
        $ti2a     = Classroom::where('name', 'TI-2A')->first();
        $ti2b     = Classroom::where('name', 'TI-2B')->first();
        $te3a     = Classroom::where('name', 'TE-3A')->first();

        $users = [
            // Teachers
            [
                'name'          => 'Budi Santoso',
                'email'         => 'teacher@axiora.com',
                'password'      => Hash::make('password'),
                'role'          => RoleEnum::LECTURER,
                'position'      => 'lecturer',
                'nidn'          => '1234567890',
                'nim'           => null,
                'image'         => null,
                'faculty_id'    => $fteknik->id,
                'department_id' => $ti->id,
                'subject_id'    => $web->id,
                'classroom_id'  => null,
            ],
            [
                'name'          => 'Siti Rahayu',
                'email'         => 'teacher2@axiora.com',
                'password'      => Hash::make('password'),
                'role'          => RoleEnum::LECTURER,
                'position'      => 'department_head',
                'nidn'          => '0987654321',
                'nim'           => null,
                'image'         => null,
                'faculty_id'    => $fteknik->id,
                'department_id' => $ti->id,
                'subject_id'    => $db->id,
                'classroom_id'  => null,
            ],
            [
                'name'          => 'Ahmad Fauzi',
                'email'         => 'teacher3@axiora.com',
                'password'      => Hash::make('password'),
                'role'          => RoleEnum::LECTURER,
                'position'      => 'dean',
                'nidn'          => '1122334455',
                'nim'           => null,
                'image'         => null,
                'faculty_id'    => $fteknik->id,
                'department_id' => $te->id,
                'subject_id'    => $jaringan->id,
                'classroom_id'  => null,
            ],
            // Students
            [
                'name'          => 'Andi Pratama',
                'email'         => 'student@axiora.com',
                'password'      => Hash::make('password'),
                'role'          => RoleEnum::STUDENT,
                'position'      => null,
                'nidn'          => null,
                'nim'           => '20210001',
                'image'         => null,
                'faculty_id'    => $fteknik->id,
                'department_id' => $ti->id,
                'subject_id'    => null,
                'classroom_id'  => $ti2a->id,
            ],
            [
                'name'          => 'Dewi Lestari',
                'email'         => 'student2@axiora.com',
                'password'      => Hash::make('password'),
                'role'          => RoleEnum::STUDENT,
                'position'      => null,
                'nidn'          => null,
                'nim'           => '20210002',
                'image'         => null,
                'faculty_id'    => $fteknik->id,
                'department_id' => $ti->id,
                'subject_id'    => null,
                'classroom_id'  => $ti2a->id,
            ],
            [
                'name'          => 'Reza Firmansyah',
                'email'         => 'student3@axiora.com',
                'password'      => Hash::make('password'),
                'role'          => RoleEnum::STUDENT,
                'position'      => null,
                'nidn'          => null,
                'nim'           => '20210003',
                'image'         => null,
                'faculty_id'    => $fteknik->id,
                'department_id' => $ti->id,
                'subject_id'    => null,
                'classroom_id'  => $ti2b->id,
            ],
            [
                'name'          => 'Maya Sari',
                'email'         => 'student4@axiora.com',
                'password'      => Hash::make('password'),
                'role'          => RoleEnum::STUDENT,
                'position'      => null,
                'nidn'          => null,
                'nim'           => '20210004',
                'image'         => null,
                'faculty_id'    => $fteknik->id,
                'department_id' => $te->id,
                'subject_id'    => null,
                'classroom_id'  => $te3a->id,
            ],
            [
                'name'          => 'Hendra Gunawan',
                'email'         => 'student5@axiora.com',
                'password'      => Hash::make('password'),
                'role'          => RoleEnum::STUDENT,
                'position'      => null,
                'nidn'          => null,
                'nim'           => '20210005',
                'image'         => null,
                'faculty_id'    => $fteknik->id,
                'department_id' => $te->id,
                'subject_id'    => null,
                'classroom_id'  => $te3a->id,
            ],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                $user,
            );
        }
    }
}
