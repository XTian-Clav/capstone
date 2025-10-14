<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReserveRoom extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reserved_by',
        'room_id',
        'status',
        'office',
        'contact',
        'email',
        'start_date',
        'end_date',
        'accept_terms',
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
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
