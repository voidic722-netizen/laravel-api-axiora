<?php

namespace App\Models;

use App\Enums\RoleEnum;
use App\Enums\SubjectTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'department_id',
        'description',
        'thumbnail',
    ];

    protected $casts = [
        'type' => SubjectTypeEnum::class,
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function classrooms(): HasMany
    {
        return $this->hasMany(Classroom::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * Users (lecturers) assigned to teach this subject.
     */
    public function lecturers(): HasMany
    {
        return $this->hasMany(User::class)
            ->where('role', RoleEnum::LECTURER);
    }
}
