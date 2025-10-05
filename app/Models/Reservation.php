<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'reservation',
        'reservation_type',
        'quantity',
        'borrower',
        'purpose',
        'submission_date',
        'status',
    ];

    public const STATUS = [
        'Pending' => 'Pending',
        'Approved' => 'Approved',
        'Rejected' => 'Rejected',
    ];

    public const RESERVATION_TYPE = [
        'Pending' => 'Pending',
        'Approved' => 'Approved',
        'Rejected' => 'Rejected',
    ];

    public const PURPOSE = [
        'Meeting' => 'Meeting',
        'Workshop' => 'Workshop',
        'Classes' => 'Classes',
        'Co-working Space' => 'Co-working Space',
    ];
}
