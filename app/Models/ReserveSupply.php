<?php

namespace App\Models;

use App\Models\ReserveSupply;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReserveSupply extends Model
{
    use HasFactory;

    protected $fillable = [
        'reserved_by',
        'user_id',
        'supply_id',
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

    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function booted()
    {
        static::updated(function (ReserveSupply $reservation) {
            $supply = $reservation->supply;
            if (! $supply) return;

            $oldStatus = $reservation->getOriginal('status');
            $newStatus = $reservation->status;

            $oldQty = $reservation->getOriginal('quantity');
            $newQty = $reservation->quantity;

            // Status changed
            if ($reservation->isDirty('status')) {
                if ($oldStatus === 'Pending' && $newStatus === 'Approved') {
                    $supply->decrement('quantity', $newQty);
                }

                if ($oldStatus === 'Approved' && $newStatus === 'Rejected') {
                    $supply->increment('quantity', $oldQty);
                }
            }

            // Quantity changed while approved
            if ($reservation->isDirty('quantity') && $newStatus === 'Approved') {
                $diff = $newQty - $oldQty;
                if ($diff > 0) {
                    $supply->decrement('quantity', $diff);
                } elseif ($diff < 0) {
                    $supply->increment('quantity', abs($diff));
                }
            }
        });

        static::deleted(function (ReserveSupply $reservation) {
            if ($reservation->status === 'Approved') {
                $reservation->supply?->increment('quantity', $reservation->quantity);
            }
        });
    }
}
