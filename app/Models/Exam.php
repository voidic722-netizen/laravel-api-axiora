<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'exam_categories',
        'classroom_ids',
        'available_at',
        'deadline_at',
        'duration_minutes',
        'questions',
    ];

    protected $casts = [
        'exam_categories'  => 'array',
        'classroom_ids'    => 'array',
        'available_at'     => 'datetime',
        'deadline_at'      => 'datetime',
        'duration_minutes' => 'integer',
        'questions'        => 'array',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function submissions(): HasMany
    {
        return $this->hasMany(ExamSubmission::class);
    }
}
