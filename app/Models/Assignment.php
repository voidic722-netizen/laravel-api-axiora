<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assignment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'task_types',
        'classroom_ids',
        'due_date',
        'max_file_size',
        'subject_id',
    ];

    protected $casts = [
        'task_types'    => 'array',
        'classroom_ids' => 'array',
        'due_date'      => 'datetime',
        'max_file_size' => 'integer',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function modules(): HasMany
    {
        return $this->hasMany(AssignmentModule::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class);
    }
}
