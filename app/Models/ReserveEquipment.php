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

            $oldStatus = $reservation->getOriginal('status');
            $newStatus = $reservation->status;

            $oldQty = $reservation->getOriginal('quantity');
            $newQty = $reservation->quantity;

            // Status changed
            if ($reservation->isDirty('status')) {
                if ($oldStatus === 'Pending' && $newStatus === 'Approved') {
                    $equipment->decrement('quantity', $newQty);
                }

                if ($oldStatus === 'Approved' && $newStatus === 'Completed') {
                    $equipment->increment('quantity', $oldQty);
                }

                if ($oldStatus === 'Approved' && $newStatus === 'Rejected') {
                    $equipment->increment('quantity', $oldQty);
                }
            }

            // Quantity changed while approved
            if ($reservation->isDirty('quantity') && $newStatus === 'Approved') {
                $diff = $newQty - $oldQty;
                if ($diff > 0) {
                    $equipment->decrement('quantity', $diff);
                } elseif ($diff < 0) {
                    $equipment->increment('quantity', abs($diff));
                }
            }
        });

        static::deleted(function (ReserveEquipment $reservation) {
            if ($reservation->status === 'Approved') {
                $reservation->equipment?->increment('quantity', $reservation->quantity);
            }
        });
    }
}
