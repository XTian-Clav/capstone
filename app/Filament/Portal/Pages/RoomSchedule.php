<?php

namespace App\Filament\Portal\Pages;

use Carbon\Carbon;
use App\Models\Room;
use Filament\Pages\Page;
use App\Models\ReserveRoom;
use Illuminate\Support\Facades\Cache;

class RoomSchedule extends Page
{
    protected string $view = 'filament.portal.pages.room-schedule';
    protected static ?string $slug = 'room-schedule';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public $currentMonth;
    public $currentYear;
    public $calendarGrid = [];
    public $reservationsByDay = [];
    public $availableYears = [];

    public function mount(): void
    {
        $this->currentMonth = (int) request('month', now()->month);
        $this->currentYear = (int) request('year', now()->year);

        $this->availableYears = Cache::remember('calendar_years', 86400, function () {
            return ReserveRoom::where('status', 'Approved')
                ->selectRaw('YEAR(start_date) as year')
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year')
                ->toArray() ?: [now()->year];
        });

        $this->generateCalendarGrid();
        $this->loadReservations();
    }

    protected function generateCalendarGrid()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1);
        $start = $date->copy()->startOfWeek(Carbon::SUNDAY);
        $end = $date->copy()->endOfMonth()->endOfWeek(Carbon::SATURDAY);

        $this->calendarGrid = [];
        $current = $start->copy();

        while ($current <= $end) {
            $this->calendarGrid[] = [
                'date' => $current->copy(),
                'isCurrentMonth' => $current->month == $this->currentMonth,
                'isToday' => $current->isToday(),
            ];
            $current->addDay();
        }
    }

    protected function loadReservations(): void
    {
        $this->reservationsByDay = [];
        $occupiedLanes = [];

        $startOfMonth = Carbon::create($this->currentYear, $this->currentMonth, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $reservations = ReserveRoom::with(['user', 'room'])
            ->with([
                'user:id,name', 
                'room:id,room_type'
            ])
            ->where('status', 'Approved')
            ->where('start_date', '<=', $endOfMonth)
            ->where('end_date', '>=', $startOfMonth)
            ->orderBy('start_date')
            ->get(['id', 'user_id', 'room_id', 'start_date', 'end_date']);

        foreach ($reservations as $res) {
            $resStart = $res->start_date;
            $resEnd = $res->end_date;

            $loopStart = $resStart->isBefore($startOfMonth) ? $startOfMonth : $resStart;
            $loopEnd = $resEnd->isAfter($endOfMonth) ? $endOfMonth : $resEnd;

            $lane = $this->findAvailableLane($occupiedLanes, $loopStart, $loopEnd);

            $date = $loopStart->copy();
            while ($date->lte($loopEnd)) {
                $occupiedLanes[$date->day][$lane] = true;

                $this->reservationsByDay[$date->day][$lane] = [
                    'room_type' => $res->room->room_type,
                    'user_name' => $res->user->name,
                    'time'      => $this->getReservationTimeLabel($date, $resStart, $resEnd),
                    'status'    => $this->getReservationStatus($date, $resStart, $resEnd),
                    'day_label' => $resStart->isSameDay($resEnd) ? null : "Day " . ($resStart->diffInDays($date) + 1),
                ];
                
                $date->addDay();
            }
        }

        foreach ($this->reservationsByDay as &$lanes) {
            ksort($lanes);
        }
    }

    private function findAvailableLane(array $occupiedLanes, Carbon $start, Carbon $end): int
    {
        $lane = 0;
        while (true) {
            $isFree = true;
            for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                if (isset($occupiedLanes[$date->day][$lane])) {
                    $isFree = false;
                    break;
                }
            }
            if ($isFree) return $lane;
            $lane++;
        }
    }

    private function getReservationStatus(Carbon $date, Carbon $start, Carbon $end): string
    {
        if ($start->isSameDay($end)) return 'same_day';
        if ($date->isSameDay($start)) return 'start';
        if ($date->isSameDay($end)) return 'end';
        return 'mid';
    }

    private function getReservationTimeLabel(Carbon $date, Carbon $start, Carbon $end): string
    {
        if ($start->isSameDay($end)) {
            return $start->format('h:i') . '-' . $end->format('h:i A');
        }
        if ($date->isSameDay($start)) return 'Start: ' . $start->format('h:i A');
        if ($date->isSameDay($end)) return 'End: ' . $end->format('h:i A');
        
        return 'Full Day';
    }
}