<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'picture',
        'room_type',
        'location',
        'capacity',
        'room_rate',
        'inclusions',
        'is_available',
    ];

    public function reservations()
    {
        return $this->hasMany(ReserveRoom::class);
    }

    protected static function booted()
    {
        // Delete picture when record is permanently deleted
        static::forceDeleted(function ($rooms) {
            if ($rooms->picture) {
                Storage::disk('public')->delete($rooms->picture);
            }
        });

        // Delete old picture when picture field is replaced
        static::updating(function ($rooms) {
            if ($rooms->isDirty('picture') && $rooms->getOriginal('picture')) {
                Storage::disk('public')->delete($rooms->getOriginal('picture'));
            }
        });
    }
}
