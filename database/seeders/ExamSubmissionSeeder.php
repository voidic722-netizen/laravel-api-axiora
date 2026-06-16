<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\ExamSubmission;
use App\Models\User;
use Illuminate\Database\Seeder;

class ExamSubmissionSeeder extends Seeder
{
    public function run(): void
    {
        $utsWeb      = Exam::where('title', 'UTS Pemrograman Web')->first();
        $utsDb       = Exam::where('title', 'UTS Basis Data')->first();
        $utsJaringan = Exam::where('title', 'UTS Jaringan Komputer')->first();

        $andi   = User::where('email', 'student@axiora.com')->first();
        $dewi   = User::where('email', 'student2@axiora.com')->first();
        $reza   = User::where('email', 'student3@axiora.com')->first();
        $maya   = User::where('email', 'student4@axiora.com')->first();
        $hendra = User::where('email', 'student5@axiora.com')->first();

        $submissions = [
            [
                'exam_id'         => $utsWeb->id,
                'user_id'         => $andi->id,
                'answers'         => ['q1' => 'a', 'q2' => 'c', 'q3' => 'b', 'q4' => 'c', 'q5' => 'b'],
                'score'           => 80,
                'correct_count'   => 4,
                'total_questions'  => 5,
                'submitted_at'    => '2025-11-03 09:28:00',
            ],
            [
                'exam_id'         => $utsWeb->id,
                'user_id'         => $dewi->id,
                'answers'         => ['q1' => 'a', 'q2' => 'c', 'q3' => 'b', 'q4' => 'c', 'q5' => 'b'],
                'score'           => 100,
                'correct_count'   => 5,
                'total_questions'  => 5,
                'submitted_at'    => '2025-11-03 09:45:00',
            ],
            [
                'exam_id'         => $utsDb->id,
                'user_id'         => $reza->id,
                'answers'         => ['q1' => 'c', 'q2' => 'b', 'q3' => 'c', 'q4' => 'b', 'q5' => 'b'],
                'score'           => 100,
                'correct_count'   => 5,
                'total_questions'  => 5,
                'submitted_at'    => '2025-11-04 09:15:00',
            ],
            [
                'exam_id'         => $utsJaringan->id,
                'user_id'         => $maya->id,
                'answers'         => ['q1' => 'd', 'q2' => 'b', 'q3' => 'b', 'q4' => 'c', 'q5' => 'c'],
                'score'           => 100,
                'correct_count'   => 5,
                'total_questions'  => 5,
                'submitted_at'    => '2025-11-05 09:10:00',
            ],
            [
                'exam_id'         => $utsJaringan->id,
                'user_id'         => $hendra->id,
                'answers'         => ['q1' => 'd', 'q2' => 'b', 'q3' => 'b', 'q4' => 'a', 'q5' => 'c'],
                'score'           => 80,
                'correct_count'   => 4,
                'total_questions'  => 5,
                'submitted_at'    => '2025-11-05 09:50:00',
            ],
        ];

        foreach ($submissions as $submission) {
            ExamSubmission::firstOrCreate(
                [
                    'exam_id' => $submission['exam_id'],
                    'user_id' => $submission['user_id'],
                ],
                $submission,
            );
        }
    }
}
