<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'picture',
        'equipment_name',
        'quantity',
        'property_no.',
        'location',
        'remarks',
    ];
}
