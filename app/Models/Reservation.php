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
}
