<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mentor extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'avatar',
        'name',
        'contact',
        'email',
        'expertise',
        'personal_info',
        'startup_id',
    ];

    const EXPERTISE = [
        'Technology' => 'Technology',
        'Agriculture' => 'Agriculture',
        'Food Service' => 'Food Service',
    ];

    public function startups()
    {
        return $this->belongsToMany(Startup::class, 'mentor_startup');
    }

    protected static function booted()
    {
        // Delete avatar when record is permanently deleted
        static::forceDeleted(function ($mentors) {
            if ($mentors->avatar) {
                Storage::disk('public')->delete($mentors->avatar);
            }
        });

        // Delete old avatar when avatar field is replaced
        static::updating(function ($mentors) {
            if ($mentors->isDirty('avatar') && $mentors->getOriginal('avatar')) {
                Storage::disk('public')->delete($mentors->getOriginal('avatar'));
            }
        });
    }
}
