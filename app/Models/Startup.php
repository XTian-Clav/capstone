<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class Startup extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'logo',
        'startup_name',
        'founder',
        'contact',
        'email',
        'description',
        'submission_date',
        'status',
    ];

    const STATUS = [
        'Pending' => 'Pending',
        'Approved' => 'Approved',
        'Rejected' => 'Rejected',
    ];

    protected $casts = [
        'submission_date' => 'datetime',
    ];

    public function mentors()
    {
        return $this->belongsToMany(Mentor::class, 'mentor_startup');
    }

    protected static function booted()
    {
        // Delete logo when record is permanently deleted
        static::forceDeleted(function ($startup) {
            if ($startup->logo) {
                Storage::disk('public')->delete($startup->logo);
            }
        });

        // Delete old logo when logo field is replaced
        static::updating(function ($startup) {
            if ($startup->isDirty('logo') && $startup->getOriginal('logo')) {
                Storage::disk('public')->delete($startup->getOriginal('logo'));
            }
        });
    }
}