<?php

namespace App\Services;

use App\Models\ReserveEquipment;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ReserveEquipmentService
{
    public function getAvailableTimesForDate(?string $date): array
    {
        if (! $date) {
            return [];
        }

        $date = Carbon::parse($date);

        // Define allowed time range: 8 AM to 5 PM
        $startPeriod = $date->copy()->hour(8)->minute(0);
        $endPeriod   = $date->copy()->hour(17)->minute(0);

        // 30-minute steps (adjust to '1 hour' if preferred)
        $times = CarbonPeriod::create($startPeriod, '30 minutes', $endPeriod);

        // Already reserved times for this date
        $reservations = ReserveEquipment::whereDate('start_time', $date)
            ->pluck('start_time')
            ->toArray();

        // Remove already reserved slots
        $available = $times->filter(function ($time) use ($reservations) {
            return ! in_array($time->format('Y-m-d H:i:s'), $reservations);
        });

        // Return dropdown-friendly key/value pairs
        $options = [];
        foreach ($available as $time) {
            $key = $time->format('H:i');
            $options[$key] = $time->format('g:i A');
        }

        return $options;
    }
}
