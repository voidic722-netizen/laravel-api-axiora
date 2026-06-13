<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamSubmission extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'exam_id',
        'user_id',
        'answers',
        'score',
        'correct_count',
        'total_questions',
        'submitted_at',
    ];

    protected $casts = [
        'answers'         => 'array',
        'score'           => 'integer',
        'correct_count'   => 'integer',
        'total_questions' => 'integer',
        'submitted_at'    => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
