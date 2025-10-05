<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Event extends Model
{
    protected $fillable = [
        'poster',
        'event',
        'description',
        'location',
        'start_date',
        'end_date',
        'status',
    ];

    public const STATUS = [
        'Pending' => 'Pending',
        'Approved' => 'Approved',
        'Rejected' => 'Rejected',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        // or 'start_date' / 'created_at' etc.
    ];

    // Register model events properly
    protected static function booted()
    {
        // Delete logo file when record is deleted
        static::deleting(function ($events) {
            if ($events->poster) {
                Storage::disk('public')->delete($events->poster);
            }
        });

        // Delete old logo file when logo field is replaced
        static::updating(function ($events) {
            if ($events->isDirty('poster') && $events->getOriginal('poster')) {
                Storage::disk('public')->delete($events->getOriginal('poster'));
            }
        });
    }
}
