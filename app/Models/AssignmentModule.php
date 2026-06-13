<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssignmentModule extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'assignment_id',
        'name',
        'file_path',
        'cloudinary_public_id',
        'format',
        'file_size',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }
}
