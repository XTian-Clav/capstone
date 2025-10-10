<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'picture',
        'item_name',
        'quantity',
        'location',
        'remarks',
    ];
}
