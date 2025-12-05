<?php

namespace App\Models;

use Exception;
use App\Models\Supply;
use App\Models\UnavailableSupply;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class UnavailableSupply extends Model
{
    protected $fillable = [
        'picture',
        'supply_id',
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

    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }

    protected static function booted()
    {
        static::created(function (UnavailableSupply $unavailableSupply) {
            self::updateParentStock($unavailableSupply, $unavailableSupply->unavailable_quantity, 'decrement');
        });

        static::updated(function (UnavailableSupply $unavailableSupply) {
            if ($unavailableSupply->isDirty('unavailable_quantity')) {
                $originalQuantity = $unavailableSupply->getOriginal('unavailable_quantity');
                $newQuantity = $unavailableSupply->unavailable_quantity;
                
                $difference = $newQuantity - $originalQuantity;
                
                $action = $difference > 0 ? 'decrement' : 'increment';
                $absoluteDifference = abs($difference);

                if ($absoluteDifference > 0) {
                    self::updateParentStock($unavailableSupply, $absoluteDifference, $action);
                }
            }
        });

        // Delete picture when record is permanently deleted
        static::deleted(function ($unavailableSupply) { // Corrected to lowercase 'deleted'
            if ($unavailableSupply->picture) {
                Storage::disk('public')->delete($unavailableSupply->picture);
            }
        });

        // Delete old picture when picture field is replaced
        static::updating(function ($unavailableSupply) {
            if ($unavailableSupply->isDirty('picture') && $unavailableSupply->getOriginal('picture')) {
                Storage::disk('public')->delete($unavailableSupply->getOriginal('picture'));
            }
        });
    }

    protected static function updateParentStock(UnavailableSupply $unavailableSupply, int $amount, string $action): void
    {
        DB::transaction(function () use ($unavailableSupply, $amount, $action) {
            $supply = $unavailableSupply->supply()->lockForUpdate()->first();

            if (!$supply) {
                throw new \Exception("Error: Parent supply not found.");
            }

            if ($action === 'decrement') {
                if ($supply->quantity < $amount) {
                    throw new Exception("Stock Integrity Violation: Cannot make {$amount} items unavailable. Only {$supply->quantity} available.");
                }
                
                $supply->decrement('quantity', $amount);
            } elseif ($action === 'increment') {
                $supply->increment('quantity', $amount);
            }
        });
    }
}