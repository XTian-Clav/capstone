<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supply extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'picture',
        'item_name',
        'quantity',
        'location',
    ];

    public function reservations()
    {
        return $this->hasMany(ReserveSupply::class);
    }

    protected static function booted()
    {
        // Delete picture when record is permanently deleted
        static::forceDeleted(function ($supplies) {
            if ($supplies->picture) {
                Storage::disk('public')->delete($supplies->picture);
            }
        });

        // Delete old picture when picture field is replaced
        static::updating(function ($supplies) {
            if ($supplies->isDirty('picture') && $supplies->getOriginal('picture')) {
                Storage::disk('public')->delete($supplies->getOriginal('picture'));
            }
        });
    }
}
