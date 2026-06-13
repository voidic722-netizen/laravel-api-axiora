<?php

namespace App\Repositories;

use App\Enums\RoleEnum;
use App\Models\Exam;
use App\Models\User;
use App\Repositories\Contracts\ExamRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ExamRepository implements ExamRepositoryInterface
{
    public function all(User $user): Collection
    {
        $query = Exam::query();

        if ($user->role === RoleEnum::STUDENT) {
            $query->whereJsonContains('classroom_ids', $user->classroom_id);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function find(int $id): ?Exam
    {
        return Exam::find($id);
    }

    public function create(array $data): Exam
    {
        return Exam::create($data);
    }

    public function update(Exam $exam, array $data): Exam
    {
        $exam->update($data);

        return $exam->fresh();
    }

    public function delete(Exam $exam): bool
    {
        return (bool) $exam->delete();
    }
}
