<?php

namespace App\Models;

use App\Enums\RoleEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classroom extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'department_id',
        'semester_id',
        'subject_id',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(User::class, 'classroom_id')
            ->where('role', RoleEnum::STUDENT);
    }

    /**
     * Lecturers are resolved by matching subject_id, not classroom_id.
     * Source: kelas_repository.js — User.findAll({ where: { role: '2', mata_pelajaran_id: kelas.mata_pelajaran_id } })
     */
    public function lecturers(): HasMany
    {
        return $this->hasMany(User::class, 'subject_id', 'subject_id')
            ->where('role', RoleEnum::LECTURER);
    }
}
