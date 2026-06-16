<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class AssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $web      = Subject::where('name', 'Pemrograman Web')->first();
        $db       = Subject::where('name', 'Basis Data')->first();
        $jaringan = Subject::where('name', 'Jaringan Komputer')->first();
        $ti2a     = Classroom::where('name', 'TI-2A')->first();
        $ti2b     = Classroom::where('name', 'TI-2B')->first();
        $te3a     = Classroom::where('name', 'TE-3A')->first();

        $assignments = [
            [
                'title'        => 'Membuat Halaman HTML Profil Diri',
                'description'  => 'Buat halaman HTML sederhana yang menampilkan profil diri Anda. Halaman harus memuat nama, foto, deskripsi singkat, dan daftar keahlian.',
                'task_types'   => ['file', 'link'],
                'classroom_ids' => [$ti2a->id],
                'due_date'     => '2025-10-01 23:59:00',
                'max_file_size' => 5,
                'subject_id'   => $web->id,
            ],
            [
                'title'        => 'Implementasi CSS Responsive Layout',
                'description'  => 'Implementasikan layout responsif menggunakan CSS Flexbox atau Grid. Halaman harus tampil baik di mobile, tablet, dan desktop.',
                'task_types'   => ['file', 'link'],
                'classroom_ids' => [$ti2a->id],
                'due_date'     => '2025-10-15 23:59:00',
                'max_file_size' => 10,
                'subject_id'   => $web->id,
            ],
            [
                'title'        => 'Laporan Perancangan Basis Data',
                'description'  => 'Buat laporan perancangan basis data untuk studi kasus sistem perpustakaan. Sertakan ERD, normalisasi hingga 3NF, dan skrip SQL.',
                'task_types'   => ['file'],
                'classroom_ids' => [$ti2b->id],
                'due_date'     => '2025-10-08 23:59:00',
                'max_file_size' => 20,
                'subject_id'   => $db->id,
            ],
            [
                'title'        => 'Analisis Paket Jaringan dengan Wireshark',
                'description'  => 'Lakukan capture dan analisis paket jaringan menggunakan Wireshark. Identifikasi protokol yang digunakan dan jelaskan alur komunikasinya.',
                'task_types'   => ['file', 'text'],
                'classroom_ids' => [$te3a->id],
                'due_date'     => '2025-10-10 23:59:00',
                'max_file_size' => 15,
                'subject_id'   => $jaringan->id,
            ],
        ];

        foreach ($assignments as $assignment) {
            Assignment::firstOrCreate(
                ['title' => $assignment['title']],
                $assignment,
            );
        }
    }
}
