<?php

namespace App\Repositories;

use App\Enums\RoleEnum;
use App\Models\Schedule;
use App\Models\User;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ScheduleRepository implements ScheduleRepositoryInterface
{
    public function all(User $user): Collection
    {
        $query = Schedule::with('classroom');

        if ($user->role === RoleEnum::STUDENT) {
            $query->where('classroom_id', $user->classroom_id);
        }

        return $query->get();
    }

    public function find(int $id): ?Schedule
    {
        return Schedule::find($id);
    }

    public function create(array $data): Schedule
    {
        return Schedule::create($data);
    }

    public function update(Schedule $schedule, array $data): Schedule
    {
        $schedule->update($data);

        return $schedule->fresh();
    }

    public function delete(Schedule $schedule): bool
    {
        return (bool) $schedule->delete();
    }
}
