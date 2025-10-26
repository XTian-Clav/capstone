<?php

namespace App\Models;

use App\Models\ReserveRoom;
use App\Models\ReserveSupply;
use App\Models\ReserveEquipment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class NonUser extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'avatar',
        'name',
        'email',
        'contact',
        'company',
        'role',
    ];

    const ROLE = [
        'Student' => 'Student',
        'PSU Employee' => 'PSU Employee',
        'External' => 'External',
    ];
    
    // Relationships to reservation tables
    public function roomReservations()
    {
        return $this->hasMany(ReserveRoom::class);
    }

    public function equipmentReservations()
    {
        return $this->hasMany(ReserveEquipment::class);
    }

    public function supplyReservations()
    {
        return $this->hasMany(ReserveSupply::class);
    }
}
