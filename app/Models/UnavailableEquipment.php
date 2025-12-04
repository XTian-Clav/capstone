<?php

namespace App\Models;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class UnavailableEquipment extends Model
{
    protected $fillable = [
        'picture',
        'equipment_id',
        'unavailable_quantity',
        'status',
        'remarks',
    ];
    
    const STATUS = [
        'Unavailable' => 'Unavailable',
        'Missing' => 'Missing',
        'Damaged' => 'Damaged',
        'Unreturned' => 'Unreturned',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    protected static function booted()
    {
        static::created(function (UnavailableEquipment $unavailableEquipment) {
            self::updateParentStock($unavailableEquipment, $unavailableEquipment->unavailable_quantity, 'decrement');
        });

        static::updated(function (UnavailableEquipment $unavailableEquipment) {
            // Only proceed if the unavailable_quantity was the field that changed
            if ($unavailableEquipment->isDirty('unavailable_quantity')) {
                $originalQuantity = $unavailableEquipment->getOriginal('unavailable_quantity');
                $newQuantity = $unavailableEquipment->unavailable_quantity;
                
                // Calculate difference: positive value means stock needs to DECREMENT.
                $difference = $newQuantity - $originalQuantity;
                
                $action = $difference > 0 ? 'decrement' : 'increment';
                $absoluteDifference = abs($difference);

                if ($absoluteDifference > 0) {
                    self::updateParentStock($unavailableEquipment, $absoluteDifference, $action);
                }
            }
        });

        // Delete picture when record is permanently deleted
        static::Deleted(function ($unavailableEquipment) {
            if ($unavailableEquipment->picture) {
                Storage::disk('public')->delete($unavailableEquipment->picture);
            }
        });

        // Delete old picture when picture field is replaced
        static::updating(function ($unavailableEquipment) {
            if ($unavailableEquipment->isDirty('picture') && $unavailableEquipment->getOriginal('picture')) {
                Storage::disk('public')->delete($unavailableEquipment->getOriginal('picture'));
            }
        });
    }

    protected static function updateParentStock(UnavailableEquipment $unavailableEquipment, int $amount, string $action): void
    {
        DB::transaction(function () use ($unavailableEquipment, $amount, $action) {
            $equipment = $unavailableEquipment->equipment()->lockForUpdate()->first();

            if (!$equipment) {
                throw new \Exception("Error: Parent equipment not found.");
            }

            if ($action === 'decrement') {
                if ($equipment->quantity < $amount) {
                    throw new Exception("Stock Integrity Violation: Cannot make {$amount} items unavailable. Only {$equipment->quantity} available.");
                }
                
                $equipment->decrement('quantity', $amount);
            } elseif ($action === 'increment') {
                $equipment->increment('quantity', $amount);
            }
        });
    }
}
