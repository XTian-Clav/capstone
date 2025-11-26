<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReserveRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'reserved_by',
        'user_id',
        'room_id',
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

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function booted()
    {
        static::updated(function ($reservation) {
            if (! $reservation->isDirty('status')) return;

            $oldStatus = $reservation->getOriginal('status');
            $newStatus = $reservation->status;

            $room = $reservation->room;
            if (! $room) return;

            if ($oldStatus === 'Pending' && $newStatus === 'Approved') {
                $room->is_available = false;
                $room->save();
            }

            if ($oldStatus === 'Approved' && in_array($newStatus, ['Completed', 'Rejected'])) {
                $room->is_available = true;
                $room->save();
            }
        });

        static::deleted(function ($reservation) {
            if ($reservation->status === 'Approved') {
                $room = $reservation->room;
                if ($room) {
                    $room->is_available = true;
                    $room->save();
                }
            }
        });
    }
}
