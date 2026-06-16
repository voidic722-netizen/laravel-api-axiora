<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $ti2a = Classroom::where('name', 'TI-2A')->first();
        $ti2b = Classroom::where('name', 'TI-2B')->first();
        $te3a = Classroom::where('name', 'TE-3A')->first();

        $schedules = [
            [
                'date'         => '2025-09-08',
                'classroom_id' => $ti2a->id,
                'topic'        => 'Pengenalan HTML & Struktur Halaman Web',
            ],
            [
                'date'         => '2025-09-15',
                'classroom_id' => $ti2a->id,
                'topic'        => 'CSS Dasar dan Box Model',
            ],
            [
                'date'         => '2025-09-22',
                'classroom_id' => $ti2a->id,
                'topic'        => 'JavaScript: Variabel, Fungsi, dan DOM',
            ],
            [
                'date'         => '2025-09-29',
                'classroom_id' => $ti2a->id,
                'topic'        => 'Fetch API dan Komunikasi dengan Backend',
            ],
            [
                'date'         => '2025-09-08',
                'classroom_id' => $ti2b->id,
                'topic'        => 'Pengenalan Basis Data dan Model Relasional',
            ],
            [
                'date'         => '2025-09-15',
                'classroom_id' => $ti2b->id,
                'topic'        => 'SQL Dasar: SELECT, INSERT, UPDATE, DELETE',
            ],
            [
                'date'         => '2025-09-22',
                'classroom_id' => $ti2b->id,
                'topic'        => 'JOIN dan Subquery',
            ],
            [
                'date'         => '2025-09-08',
                'classroom_id' => $te3a->id,
                'topic'        => 'Model OSI dan TCP/IP',
            ],
            [
                'date'         => '2025-09-15',
                'classroom_id' => $te3a->id,
                'topic'        => 'IP Addressing dan Subnetting',
            ],
            [
                'date'         => '2025-09-22',
                'classroom_id' => $te3a->id,
                'topic'        => 'Routing dan Switching',
            ],
        ];

        foreach ($schedules as $schedule) {
            Schedule::firstOrCreate(
                [
                    'date'         => $schedule['date'],
                    'classroom_id' => $schedule['classroom_id'],
                ],
                $schedule,
            );
        }
    }
}
