<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'picture',
        'event',
        'description',
        'location',
        'start_date',
        'end_date',
        'status',
        'attendance',
    ];

    const STATUS = [
        'Upcoming' => 'Upcoming',
        'Ongoing' => 'Ongoing',
        'Completed' => 'Completed',
        'Cancelled' => 'Cancelled',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'attendance' => 'array',
    ];

    protected static function booted()
    {
        // Delete poster when record is permanently deleted
        static::forceDeleted(function ($events) {
            if ($events->poster) {
                Storage::disk('public')->delete($events->poster);
            }
        });

        // Delete old poster when poster field is replaced
        static::updating(function ($events) {
            if ($events->isDirty('poster') && $events->getOriginal('poster')) {
                Storage::disk('public')->delete($events->getOriginal('poster'));
            }
        });
    }
}
