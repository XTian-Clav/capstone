<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class EventUser extends Pivot
{
    protected $table = 'event_users';

    protected $fillable = [
        'event_id',
        'user_id',
        'is_attending',
    ];

    protected $casts = [
        'is_attending' => 'boolean',
    ];

    // Optional: define relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
