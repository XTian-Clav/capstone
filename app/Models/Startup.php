<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Startup extends Model
{
    protected $fillable = [
        'logo',
        'startup_name',
        'founder',
        'submission_date',
        'status',
    ];
}
