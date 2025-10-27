<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class Startup extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'logo',
        'startup_name',
        'founder',
        'description',
        'status',
    ];

    const STATUS = [
        'Pending' => 'Pending',
        'Approved' => 'Approved',
        'Rejected' => 'Rejected',
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