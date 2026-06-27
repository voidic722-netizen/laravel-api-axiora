<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Classroom;
use App\Models\Department;
use App\Models\Exam;
use App\Models\ExamSubmission;
use App\Models\Faculty;
use App\Models\Schedule;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class MassiveDataSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        
        $departments = Department::all();
        $semesters = Semester::all();
        if ($departments->isEmpty() || $semesters->isEmpty()) {
            return;
        }

        $subjectNames = [
            'Kecerdasan Buatan', 'Pengembangan Aplikasi Mobile', 'Sistem Operasi',
            'Rekayasa Perangkat Lunak', 'Struktur Data dan Algoritma', 'Keamanan Siber',
            'Kalkulus Lanjut', 'Jaringan Syaraf Tiruan', 'Data Mining', 'Desain Antarmuka Pengguna',
            'Sistem Tertanam', 'Manajemen Proyek TI', 'Pengolahan Citra Digital', 'Komputasi Awan', 'Internet of Things'
        ];

        // Generate 15 extra subjects
        $subjects = [];
        for ($i = 0; $i < 15; $i++) {
            $dept = $departments->random();
            $subjects[] = Subject::create([
                'name' => $faker->randomElement($subjectNames) . ' ' . rand(1, 3),
                'type' => $faker->randomElement(['compulsory', 'general']),
                'department_id' => $dept->id,
                'description' => $faker->sentence(10),
            ]);
        }

        $allSubjects = Subject::all();

        // Generate 20 Classrooms
        $classrooms = [];
        for ($i = 0; $i < 20; $i++) {
            $dept = $departments->random();
            $sub = $allSubjects->random();
            $sem = $semesters->random();
            
            $classrooms[] = Classroom::create([
                'name' => strtoupper($faker->bothify('##-?#')),
                'department_id' => $dept->id,
                'semester_id' => $sem->id,
                'subject_id' => $sub->id,
            ]);
        }

        $allClassrooms = Classroom::all();

        // Generate 20 Teachers
        $teachers = [];
        for ($i = 0; $i < 20; $i++) {
            $dept = $departments->random();
            $teachers[] = User::create([
                'name' => 'Dosen ' . $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'role' => RoleEnum::LECTURER,
                'position' => 'lecturer',
                'nidn' => $faker->numerify('##########'),
                'faculty_id' => $dept->faculty_id,
                'department_id' => $dept->id,
                'subject_id' => $allSubjects->random()->id,
            ]);
        }

        // Generate 100 Students
        $students = [];
        for ($i = 0; $i < 100; $i++) {
            $class = $allClassrooms->random();
            $dept = Department::find($class->department_id);
            $students[] = User::create([
                'name' => 'Mhs ' . $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'role' => RoleEnum::STUDENT,
                'nim' => $faker->numerify('202#####'),
                'faculty_id' => $dept->faculty_id,
                'department_id' => $dept->id,
                'classroom_id' => $class->id,
            ]);
        }

        // Base date: June 2026 for schedules
        $juneStart = Carbon::create(2026, 6, 1, 8, 0, 0);
        // Deadline date: 20 July 2026
        $july20 = Carbon::create(2026, 7, 20, 23, 59, 59);
        
        $assignmentTitles = [
            'Laporan Praktikum', 'Analisis Kasus', 'Implementasi Kode',
            'Review Jurnal', 'Desain Prototipe', 'Makalah Akhir', 'Tugas Kelompok'
        ];

        // Generate 30 Assignments
        $assignments = [];
        for ($i = 0; $i < 30; $i++) {
            $sub = $allSubjects->random();
            $dueDate = $july20->copy()->subHours(rand(0, 12));
            
            $validClassrooms = $allClassrooms->where('subject_id', $sub->id)->pluck('id')->toArray();
            if (empty($validClassrooms)) continue;
            
            $assignClassrooms = $faker->randomElements($validClassrooms, rand(1, min(3, count($validClassrooms))));

            $assign = Assignment::create([
                'title' => $faker->randomElement($assignmentTitles) . ' ' . rand(1, 5),
                'description' => $faker->paragraph,
                'task_types' => [$faker->randomElement(['document', 'video', 'image', 'audio'])],
                'subject_id' => $sub->id,
                'classroom_ids' => $assignClassrooms,
                'due_date' => $dueDate,
                'max_file_size' => 10,
            ]);
            
            $assignments[] = $assign;
        }

        $examTitles = [
            'Ujian Teori', 'Ujian Praktikum', 'Kuis Pilihan Ganda', 'Evaluasi Pemahaman', 'Ujian Lisan'
        ];

        // Generate 20 Exams
        for ($i = 0; $i < 20; $i++) {
            $sub = $allSubjects->random();
            $examDate = $july20->copy()->subHours(rand(0, 12));
            
            $validClassrooms = $allClassrooms->where('subject_id', $sub->id)->pluck('id')->toArray();
            if (empty($validClassrooms)) continue;
            
            $examClassrooms = $faker->randomElements($validClassrooms, rand(1, min(3, count($validClassrooms))));

            $questions = [];
            for ($q = 1; $q <= 5; $q++) {
                $questions[] = [
                    'id' => 'q' . $q . '_' . Str::random(5),
                    'question' => $faker->sentence . '?',
                    'image' => null,
                    'options' => [
                        ['id' => 'a', 'text' => $faker->word, 'is_correct' => true],
                        ['id' => 'b', 'text' => $faker->word, 'is_correct' => false],
                        ['id' => 'c', 'text' => $faker->word, 'is_correct' => false],
                        ['id' => 'd', 'text' => $faker->word, 'is_correct' => false],
                    ],
                ];
            }

            $exam = Exam::create([
                'title' => $faker->randomElement($examTitles) . ' ' . rand(1, 3),
                'description' => $faker->sentence,
                'exam_categories' => [$faker->randomElement(['UTS', 'UAS', 'Kuis'])],
                'classroom_ids' => $examClassrooms,
                'available_at' => $examDate->copy()->subDays(1),
                'deadline_at' => $examDate,
                'duration_minutes' => $faker->randomElement([60, 90, 120]),
                'questions' => $questions,
            ]);
        }

        $scheduleTopics = [
            'Pengenalan Konsep Dasar', 'Studi Kasus Lanjutan', 'Evaluasi Proyek',
            'Diskusi Teori', 'Praktikum di Laboratorium', 'Presentasi Kelompok', 'Review Materi'
        ];

        // Generate 40 Schedules
        for ($i = 0; $i < 40; $i++) {
            $class = $allClassrooms->random();
            $teacher = collect($teachers)->random();
            $schedDate = $juneStart->copy()->addDays(rand(0, 29))->addHours(rand(0, 10));
            
            Schedule::create([
                'classroom_id' => $class->id,
                'topic' => 'Pertemuan ' . rand(1, 14) . ': ' . $faker->randomElement($scheduleTopics),
                'date' => $schedDate->toDateString(),
            ]);
        }

        // Student Submissions for Assignments
        $allStudents = User::where('role', RoleEnum::STUDENT)->get();
        foreach ($assignments as $assign) {
            $studentIds = collect();
            foreach ($assign->classroom_ids as $cId) {
                $studentIds = $studentIds->merge($allStudents->where('classroom_id', $cId)->pluck('id'));
            }
            $studentIds = $studentIds->unique();

            foreach ($studentIds as $sId) {
                if ($faker->boolean(60)) {
                    $isLate = $faker->boolean(20);
                    $submitDate = $assign->due_date->copy();
                    if ($isLate) {
                        $submitDate->addHours(rand(1, 24));
                    } else {
                        $submitDate->subHours(rand(1, 48));
                    }

                    AssignmentSubmission::create([
                        'assignment_id' => $assign->id,
                        'user_id' => $sId,
                        'status' => $isLate ? 'late' : 'submitted',
                        'files' => [
                            [
                                'fileName' => 'tugas_' . rand(100, 999) . '.pdf',
                                'filePath' => 'assignments/dummy.pdf',
                                'fileSize' => rand(1, 5) . 'MB'
                            ]
                        ],
                        'grade' => $faker->boolean(70) ? rand(60, 100) : null,
                        'feedback' => $faker->boolean(30) ? $faker->sentence : null,
                        'submitted_at' => $submitDate,
                    ]);
                }
            }
        }
    }
}
