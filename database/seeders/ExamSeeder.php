<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Exam;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    public function run(): void
    {
        $ti2a = Classroom::where('name', 'TI-2A')->first();
        $ti2b = Classroom::where('name', 'TI-2B')->first();
        $te3a = Classroom::where('name', 'TE-3A')->first();

        $exams = [
            [
                'title'             => 'UTS Pemrograman Web',
                'description'       => 'Ujian Tengah Semester mata kuliah Pemrograman Web. Mencakup materi HTML, CSS, dan JavaScript dasar.',
                'exam_categories'   => ['UTS'],
                'classroom_ids'     => [$ti2a->id],
                'available_at'      => '2025-11-03 08:00:00',
                'deadline_at'       => '2025-11-03 10:00:00',
                'duration_minutes'  => 90,
                'questions'         => [
                    [
                        'id'      => 'q1',
                        'question' => 'Apa kepanjangan dari HTML?',
                        'image'   => null,
                        'options' => [
                            ['id' => 'a', 'text' => 'HyperText Markup Language', 'is_correct' => true],
                            ['id' => 'b', 'text' => 'HighText Machine Language', 'is_correct' => false],
                            ['id' => 'c', 'text' => 'HyperText Machine Language', 'is_correct' => false],
                            ['id' => 'd', 'text' => 'HyperTool Markup Language', 'is_correct' => false],
                        ],
                    ],
                    [
                        'id'      => 'q2',
                        'question' => 'Properti CSS manakah yang digunakan untuk mengubah warna teks?',
                        'image'   => null,
                        'options' => [
                            ['id' => 'a', 'text' => 'font-color', 'is_correct' => false],
                            ['id' => 'b', 'text' => 'text-color', 'is_correct' => false],
                            ['id' => 'c', 'text' => 'color', 'is_correct' => true],
                            ['id' => 'd', 'text' => 'background-color', 'is_correct' => false],
                        ],
                    ],
                    [
                        'id'      => 'q3',
                        'question' => 'Manakah cara yang benar untuk mendeklarasikan variabel di JavaScript modern?',
                        'image'   => null,
                        'options' => [
                            ['id' => 'a', 'text' => 'var x = 10', 'is_correct' => false],
                            ['id' => 'b', 'text' => 'let x = 10', 'is_correct' => true],
                            ['id' => 'c', 'text' => 'int x = 10', 'is_correct' => false],
                            ['id' => 'd', 'text' => 'dim x = 10', 'is_correct' => false],
                        ],
                    ],
                    [
                        'id'      => 'q4',
                        'question' => 'Tag HTML mana yang digunakan untuk membuat tautan (link)?',
                        'image'   => null,
                        'options' => [
                            ['id' => 'a', 'text' => '<link>', 'is_correct' => false],
                            ['id' => 'b', 'text' => '<href>', 'is_correct' => false],
                            ['id' => 'c', 'text' => '<a>', 'is_correct' => true],
                            ['id' => 'd', 'text' => '<url>', 'is_correct' => false],
                        ],
                    ],
                    [
                        'id'      => 'q5',
                        'question' => 'Model CSS manakah yang digunakan untuk layout dua dimensi?',
                        'image'   => null,
                        'options' => [
                            ['id' => 'a', 'text' => 'Flexbox', 'is_correct' => false],
                            ['id' => 'b', 'text' => 'Grid', 'is_correct' => true],
                            ['id' => 'c', 'text' => 'Float', 'is_correct' => false],
                            ['id' => 'd', 'text' => 'Position', 'is_correct' => false],
                        ],
                    ],
                ],
            ],
            [
                'title'             => 'UTS Basis Data',
                'description'       => 'Ujian Tengah Semester mata kuliah Basis Data. Mencakup materi model relasional, SQL, dan normalisasi.',
                'exam_categories'   => ['UTS'],
                'classroom_ids'     => [$ti2b->id],
                'available_at'      => '2025-11-04 08:00:00',
                'deadline_at'       => '2025-11-04 10:00:00',
                'duration_minutes'  => 90,
                'questions'         => [
                    [
                        'id'      => 'q1',
                        'question' => 'Perintah SQL mana yang digunakan untuk mengambil data dari tabel?',
                        'image'   => null,
                        'options' => [
                            ['id' => 'a', 'text' => 'GET', 'is_correct' => false],
                            ['id' => 'b', 'text' => 'FETCH', 'is_correct' => false],
                            ['id' => 'c', 'text' => 'SELECT', 'is_correct' => true],
                            ['id' => 'd', 'text' => 'RETRIEVE', 'is_correct' => false],
                        ],
                    ],
                    [
                        'id'      => 'q2',
                        'question' => 'Apa itu Primary Key dalam basis data relasional?',
                        'image'   => null,
                        'options' => [
                            ['id' => 'a', 'text' => 'Kolom yang boleh bernilai NULL', 'is_correct' => false],
                            ['id' => 'b', 'text' => 'Kolom yang mengidentifikasi setiap baris secara unik', 'is_correct' => true],
                            ['id' => 'c', 'text' => 'Kolom yang menghubungkan dua tabel', 'is_correct' => false],
                            ['id' => 'd', 'text' => 'Kolom pertama dalam tabel', 'is_correct' => false],
                        ],
                    ],
                    [
                        'id'      => 'q3',
                        'question' => 'Normalisasi 1NF mensyaratkan bahwa setiap kolom harus...',
                        'image'   => null,
                        'options' => [
                            ['id' => 'a', 'text' => 'Memiliki nilai default', 'is_correct' => false],
                            ['id' => 'b', 'text' => 'Bersifat unik', 'is_correct' => false],
                            ['id' => 'c', 'text' => 'Bersifat atomik (tidak dapat dibagi)', 'is_correct' => true],
                            ['id' => 'd', 'text' => 'Berisi angka', 'is_correct' => false],
                        ],
                    ],
                    [
                        'id'      => 'q4',
                        'question' => 'Perintah SQL mana yang menggabungkan baris dari dua tabel berdasarkan kondisi tertentu?',
                        'image'   => null,
                        'options' => [
                            ['id' => 'a', 'text' => 'UNION', 'is_correct' => false],
                            ['id' => 'b', 'text' => 'JOIN', 'is_correct' => true],
                            ['id' => 'c', 'text' => 'MERGE', 'is_correct' => false],
                            ['id' => 'd', 'text' => 'COMBINE', 'is_correct' => false],
                        ],
                    ],
                    [
                        'id'      => 'q5',
                        'question' => 'Apa fungsi dari Foreign Key?',
                        'image'   => null,
                        'options' => [
                            ['id' => 'a', 'text' => 'Mengidentifikasi baris secara unik', 'is_correct' => false],
                            ['id' => 'b', 'text' => 'Menjaga integritas referensial antar tabel', 'is_correct' => true],
                            ['id' => 'c', 'text' => 'Mempercepat pencarian data', 'is_correct' => false],
                            ['id' => 'd', 'text' => 'Membatasi panjang data', 'is_correct' => false],
                        ],
                    ],
                ],
            ],
            [
                'title'             => 'UTS Jaringan Komputer',
                'description'       => 'Ujian Tengah Semester mata kuliah Jaringan Komputer. Mencakup materi model OSI, TCP/IP, dan pengalamatan IP.',
                'exam_categories'   => ['UTS'],
                'classroom_ids'     => [$te3a->id],
                'available_at'      => '2025-11-05 08:00:00',
                'deadline_at'       => '2025-11-05 10:00:00',
                'duration_minutes'  => 90,
                'questions'         => [
                    [
                        'id'      => 'q1',
                        'question' => 'Model OSI terdiri dari berapa lapisan?',
                        'image'   => null,
                        'options' => [
                            ['id' => 'a', 'text' => '4', 'is_correct' => false],
                            ['id' => 'b', 'text' => '5', 'is_correct' => false],
                            ['id' => 'c', 'text' => '6', 'is_correct' => false],
                            ['id' => 'd', 'text' => '7', 'is_correct' => true],
                        ],
                    ],
                    [
                        'id'      => 'q2',
                        'question' => 'Protokol mana yang digunakan untuk resolusi nama domain menjadi alamat IP?',
                        'image'   => null,
                        'options' => [
                            ['id' => 'a', 'text' => 'DHCP', 'is_correct' => false],
                            ['id' => 'b', 'text' => 'DNS', 'is_correct' => true],
                            ['id' => 'c', 'text' => 'HTTP', 'is_correct' => false],
                            ['id' => 'd', 'text' => 'FTP', 'is_correct' => false],
                        ],
                    ],
                    [
                        'id'      => 'q3',
                        'question' => 'Berapa bit panjang alamat IPv4?',
                        'image'   => null,
                        'options' => [
                            ['id' => 'a', 'text' => '16 bit', 'is_correct' => false],
                            ['id' => 'b', 'text' => '32 bit', 'is_correct' => true],
                            ['id' => 'c', 'text' => '64 bit', 'is_correct' => false],
                            ['id' => 'd', 'text' => '128 bit', 'is_correct' => false],
                        ],
                    ],
                    [
                        'id'      => 'q4',
                        'question' => 'Lapisan OSI mana yang bertanggung jawab atas routing paket?',
                        'image'   => null,
                        'options' => [
                            ['id' => 'a', 'text' => 'Data Link Layer', 'is_correct' => false],
                            ['id' => 'b', 'text' => 'Transport Layer', 'is_correct' => false],
                            ['id' => 'c', 'text' => 'Network Layer', 'is_correct' => true],
                            ['id' => 'd', 'text' => 'Session Layer', 'is_correct' => false],
                        ],
                    ],
                    [
                        'id'      => 'q5',
                        'question' => 'Protokol TCP berbeda dari UDP karena TCP bersifat...',
                        'image'   => null,
                        'options' => [
                            ['id' => 'a', 'text' => 'Lebih cepat', 'is_correct' => false],
                            ['id' => 'b', 'text' => 'Connectionless', 'is_correct' => false],
                            ['id' => 'c', 'text' => 'Connection-oriented dan reliable', 'is_correct' => true],
                            ['id' => 'd', 'text' => 'Tidak menggunakan port', 'is_correct' => false],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($exams as $exam) {
            Exam::firstOrCreate(
                ['title' => $exam['title']],
                $exam,
            );
        }
    }
}
