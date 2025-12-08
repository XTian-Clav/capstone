<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class Startup extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'logo',
        'document',
        'url',
        'startup_name',
        'founder',
        'members',
        'description',
        'status',
        'admin_comment',
    ];

    protected $casts = [
        'members' => 'array',
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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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

    protected function getLogoUrlAttribute(): string
    {
        if ($this->logo && File::exists(storage_path('app/public/' . $this->logo))) {
            return asset('storage/' . $this->logo);
        }
        return asset('storage/default/no-image.png');
    }
}