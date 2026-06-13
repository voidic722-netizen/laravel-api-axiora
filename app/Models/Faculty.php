<?php

namespace App\Models;

use App\Enums\PositionEnum;
use App\Enums\RoleEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faculty extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'thumbnail',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    public function dean(): HasOne
    {
        return $this->hasOne(User::class)
            ->where('position', PositionEnum::DEAN);
    }

    public function lecturers(): HasMany
    {
        return $this->hasMany(User::class)
            ->where('role', RoleEnum::LECTURER);
    }

    public function students(): HasMany
    {
        return $this->hasMany(User::class)
            ->where('role', RoleEnum::STUDENT);
    }
}
