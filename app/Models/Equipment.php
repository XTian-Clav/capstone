<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'picture',
        'equipment_name',
        'quantity',
        'property_no',
        'location',
    ];

    public function reservations()
    {
        return $this->hasMany(ReserveEquipment::class);
    }

    protected static function booted()
    {
        // Delete picture when record is permanently deleted
        static::forceDeleted(function ($equipment) {
            if ($equipment->picture) {
                Storage::disk('public')->delete($equipment->picture);
            }
        });

        // Delete old picture when picture field is replaced
        static::updating(function ($equipment) {
            if ($equipment->isDirty('picture') && $equipment->getOriginal('picture')) {
                Storage::disk('public')->delete($equipment->getOriginal('picture'));
            }
        });
    }
}
