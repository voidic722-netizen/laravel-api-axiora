<?php

namespace App\Models;

use App\Enums\SubmissionStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssignmentSubmission extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'assignment_id',
        'user_id',
        'files',
        'status',
        'submitted_at',
        'grade',
        'feedback',
    ];

    protected $casts = [
        'files'        => 'array',
        'status'       => SubmissionStatusEnum::class,
        'submitted_at' => 'datetime',
        'grade'        => 'integer',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
