<?php

namespace App\Models;

use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Classroom extends Model
{
    protected $guarded = ['id'];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($class) {
            do {
                $code = strtoupper(Str::random(6));
            } while (static::classCodeExists($code));

            $class->class_code = $code;
        });
    }

    protected static function classCodeExists($code)
    {
        return static::where('class_code', $code)->exists();
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'classroom_id', 'id');
    }
}
