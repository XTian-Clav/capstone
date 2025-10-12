<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReserveEquipment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reserved_by',
        'status',
        'equipment_id',
        'quantity',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    const STATUS = [
        'Pending' => 'Pending',
        'Approved' => 'Approved',
        'Rejected' => 'Rejected',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

}
