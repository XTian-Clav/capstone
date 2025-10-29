<?php

namespace App\Models;

use App\Models\Startup;
use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    protected $fillable = [
        'startup_id',
        'title',
        'description',
        'is_done',
        'admin_comments',
        'url',
        'summary',
    ];

    public function startup()
    {
        return $this->belongsTo(Startup::class);
    }
}
