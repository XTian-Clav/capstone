<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class Startup extends Model
{
    protected $fillable = [
        'logo',
        'startup_name',
        'founder',
        'submission_date',
        'status',
    ];

    public const STATUS = [
        'Pending' => 'Pending',
        'Approved' => 'Approved',
        'Rejected' => 'Rejected',
    ];

    // Register model events properly
    protected static function booted()
    {
        // Delete logo file when record is deleted
        static::deleting(function ($startup) {
            if ($startup->logo) {
                Storage::disk('public')->delete($startup->logo);
            }
        });

        // Delete old logo file when logo field is replaced
        static::updating(function ($startup) {
            if ($startup->isDirty('logo') && $startup->getOriginal('logo')) {
                Storage::disk('public')->delete($startup->getOriginal('logo'));
            }
        });
    }

    use SoftDeletes;
}