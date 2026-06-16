<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\User;
use Illuminate\Database\Seeder;

class AssignmentSubmissionSeeder extends Seeder
{
    public function run(): void
    {
        $tugas1 = Assignment::where('title', 'Membuat Halaman HTML Profil Diri')->first();
        $tugas2 = Assignment::where('title', 'Implementasi CSS Responsive Layout')->first();
        $tugas3 = Assignment::where('title', 'Laporan Perancangan Basis Data')->first();
        $tugas4 = Assignment::where('title', 'Analisis Paket Jaringan dengan Wireshark')->first();

        $andi  = User::where('email', 'student@axiora.com')->first();
        $dewi  = User::where('email', 'student2@axiora.com')->first();
        $reza  = User::where('email', 'student3@axiora.com')->first();
        $maya  = User::where('email', 'student4@axiora.com')->first();
        $hendra = User::where('email', 'student5@axiora.com')->first();

        $submissions = [
            [
                'assignment_id' => $tugas1->id,
                'user_id'       => $andi->id,
                'files'         => [],
                'status'        => 'submitted',
                'submitted_at'  => '2025-09-30 18:23:00',
                'grade'         => 88,
                'feedback'      => 'Struktur HTML sudah baik. Perlu ditambahkan semantik HTML5 yang lebih lengkap.',
            ],
            [
                'assignment_id' => $tugas1->id,
                'user_id'       => $dewi->id,
                'files'         => [],
                'status'        => 'submitted',
                'submitted_at'  => '2025-09-30 20:11:00',
                'grade'         => 92,
                'feedback'      => 'Excellent! Desain bersih dan kode terorganisir dengan baik.',
            ],
            [
                'assignment_id' => $tugas2->id,
                'user_id'       => $andi->id,
                'files'         => [],
                'status'        => 'submitted',
                'submitted_at'  => '2025-10-14 22:45:00',
                'grade'         => null,
                'feedback'      => null,
            ],
            [
                'assignment_id' => $tugas2->id,
                'user_id'       => $dewi->id,
                'files'         => [],
                'status'        => 'submitted',
                'submitted_at'  => '2025-10-15 10:30:00',
                'grade'         => null,
                'feedback'      => null,
            ],
            [
                'assignment_id' => $tugas3->id,
                'user_id'       => $reza->id,
                'files'         => [],
                'status'        => 'submitted',
                'submitted_at'  => '2025-10-07 14:00:00',
                'grade'         => 85,
                'feedback'      => 'ERD sudah lengkap. Normalisasi perlu diperdalam pada relasi many-to-many.',
            ],
            [
                'assignment_id' => $tugas4->id,
                'user_id'       => $maya->id,
                'files'         => [],
                'status'        => 'submitted',
                'submitted_at'  => '2025-10-09 16:15:00',
                'grade'         => 90,
                'feedback'      => 'Analisis paket sangat detail dan terstruktur.',
            ],
            [
                'assignment_id' => $tugas4->id,
                'user_id'       => $hendra->id,
                'files'         => [],
                'status'        => 'submitted',
                'submitted_at'  => '2025-10-10 09:00:00',
                'grade'         => null,
                'feedback'      => null,
            ],
        ];

        foreach ($submissions as $submission) {
            AssignmentSubmission::firstOrCreate(
                [
                    'assignment_id' => $submission['assignment_id'],
                    'user_id'       => $submission['user_id'],
                ],
                $submission,
            );
        }
    }
}
