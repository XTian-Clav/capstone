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

    protected function loadReservations()
    {
        $this->reservationsByDay = [];

        $startOfMonth = Carbon::create($this->currentYear, $this->currentMonth, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $reservations = ReserveRoom::with(['user', 'room'])
            ->where('status', 'Approved')
            ->where('start_date', '<=', $endOfMonth)
            ->where('end_date', '>=', $startOfMonth)
            ->get();
        
        foreach ($reservations as $res) {
            $resStart = Carbon::parse($res->start_date);
            $resEnd = Carbon::parse($res->end_date);
        
            $timeString = $resStart->format('h:i A') . ' - ' . $resEnd->format('h:i A');
        
            $loopStart = $resStart->isBefore($startOfMonth) ? $startOfMonth->copy() : $resStart->copy();
            $loopEnd = $resEnd->isAfter($endOfMonth) ? $endOfMonth->copy() : $resEnd->copy();
        
            for ($date = $loopStart->copy(); $date->lte($loopEnd); $date->addDay()) {
                $this->reservationsByDay[$date->day][] = [
                    'room_type' => $res->room->room_type,
                    'user_name' => $res->user->name,
                    'time' => $timeString, 
                ];
            }
        }
    }
}