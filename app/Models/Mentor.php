<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mentor extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'avatar',
        'firstname',
        'lastname',
        'contact',
        'email',
        'expertise',
        'personal_info',
        'startup_id',
    ];

    public const EXPERTISE = [
        'Technology' => 'Technology',
        'Agriculture' => 'Agriculture',
        'Food Service' => 'Food Service',
    ];

    public function startups()
    {
        return $this->belongsToMany(Startup::class, 'mentor_startup');
    }

    public function getFullNameAttribute()
    {
        return "{$this->firstname} {$this->lastname}";
    }
}
