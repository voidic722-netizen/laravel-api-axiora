<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\Schedule;
use App\Models\User;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ScheduleService
{
    public function __construct(
        protected ScheduleRepositoryInterface $scheduleRepository,
    ) {
    }

    /**
     * Source: jadwal_repository.js — getJadwalList
     */
    public function list(User $user): Collection
    {
        return $this->scheduleRepository->all($user);
    }

    /**
     * Source: jadwal_repository.js — createJadwal
     */
    public function create(array $data): Schedule
    {
        return $this->scheduleRepository->create($data);
    }

    /**
     * Source: jadwal_repository.js — updateJadwal
     */
    public function update(int $id, array $data): Schedule
    {
        $schedule = $this->scheduleRepository->find($id);

        if (!$schedule) {
            throw new ApiException('Schedule not found', 404);
        }

        return $this->scheduleRepository->update($schedule, $data);
    }

    /**
     * Source: jadwal_repository.js — deleteJadwal
     */
    public function delete(int $id): void
    {
        $schedule = $this->scheduleRepository->find($id);

        if (!$schedule) {
            throw new ApiException('Schedule not found', 404);
        }

        $this->scheduleRepository->delete($schedule);
    }
}
