<?php

namespace App\Models;

use Exception;
use App\Models\User;
use App\Models\ReserveEquipment;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReserveEquipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'reserved_by',
        'user_id',
        'equipment_id',
        'quantity',
        'status',
        'company',
        'contact',
        'email',
        'start_date',
        'end_date',
        'accept_terms',
        'admin_comment',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'accept_terms' => 'boolean',
    ];

    const STATUS = [
        'Pending' => 'Pending',
        'Approved' => 'Approved',
        'Rejected' => 'Rejected',
        'Completed' => 'Completed',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function booted()
    {
        static::updated(function (ReserveEquipment $reservation) {
            $equipment = $reservation->equipment;
            if (! $equipment) return;

            DB::transaction(function () use ($reservation, $equipment) {
                $oldStatus = $reservation->getOriginal('status');
                $newStatus = $reservation->status;

                $oldQty = $reservation->getOriginal('quantity');
                $newQty = $reservation->quantity;

                // CASE 1: Status changed
                if ($reservation->isDirty('status')) {

                    // Pending → Approved: deduct stock
                    if ($oldStatus === 'Pending' && $newStatus === 'Approved') {
                        if ($equipment->quantity < $newQty) {
                            throw new Exception("Not enough stock for {$equipment->equipment_name}");
                        }
                        $equipment->decrement('quantity', $newQty);
                    }

                    // Approved → Completed: return stock
                    if ($oldStatus === 'Approved' && $newStatus === 'Completed') {
                        $equipment->increment('quantity', $oldQty);
                    }

                    // Approved → Rejected: return stock
                    if ($oldStatus === 'Approved' && $newStatus === 'Rejected') {
                        $equipment->increment('quantity', $oldQty);
                    }

                // CASE 2: Quantity changed while status is Approved
                } elseif ($reservation->isDirty('quantity') && $newStatus === 'Approved') {
                    $diff = $newQty - $oldQty;

                    if ($diff > 0) {
                        // Deduct more stock, check if enough
                        if ($equipment->quantity < $diff) {
                            throw new \Exception("Not enough stock for {$equipment->equipment_name}");
                        }
                        $equipment->decrement('quantity', $diff);
                    } elseif ($diff < 0) {
                        // Return excess stock
                        $equipment->increment('quantity', abs($diff));
                    }
                }
            });
        });

        // Soft delete: return stock if approved
        static::deleted(function (ReserveEquipment $reservation) {
            if ($reservation->status === 'Approved') {
                $reservation->equipment?->increment('quantity', $reservation->quantity);
            }
        });
    }
}
