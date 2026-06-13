<?php

namespace App\Repositories\Contracts;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface ScheduleRepositoryInterface
{
    /**
     * List schedules. Students see only schedules for their own classroom.
     * Source: jadwal_repository.js — getJadwalList
     */
    public function all(User $user): Collection;

    public function find(int $id): ?Schedule;

    public function create(array $data): Schedule;

    public function update(Schedule $schedule, array $data): Schedule;

    public function delete(Schedule $schedule): bool;
}
