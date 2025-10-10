<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'picture',
        'room_name',
        'room_type',
        'location',
        'capacity',
        'room_rate',
        'inclusions',
        'is_available',
    ];
}
