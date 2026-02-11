<?php

namespace App\Filament\Portal\Pages;

use UnitEnum;
use App\Models\Room;
use Filament\Pages\Page;
use App\Models\ReserveRoom;
use Filament\Actions\Action;

class RoomReport extends Page
{
    protected static ?int $navigationSort = 1;
    protected static string|UnitEnum|null $navigationGroup = 'Generate Reports';
    
    protected string $view = 'filament.portal.pages.room-report';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('super_admin');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print_report')
                ->label('Print')
                ->icon('heroicon-s-printer')
                ->color('info')
                ->url(route('RoomReport', request()->only(['month', 'year'])))
                ->openUrlInNewTab(),
        ];
    }

    public $rooms;
    public $totalRooms = 0;
    public $totalRevenue = 0;
    public $totalLostRevenue = 0;
    public $availableYears = [];

    public function mount(): void
    {
        if (!request()->query('month') && !request()->query('year')) {
            $this->redirect(request()->fullUrlWithQuery([
                'month' => now()->month,
                'year' => now()->year,
            ]));

            return;
        }

        $month = request('month'); 
        $year = request('year', now()->year);

        $this->availableYears = ReserveRoom::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        if (empty($this->availableYears)) {
            $this->availableYears = [now()->year];
        }

        $this->rooms = Room::withCount([
            'reservations as approved_count' => function ($q) use ($month, $year) {
                $q->where('status', 'Approved')->whereYear('created_at', $year);
                if ($month) $q->whereMonth('created_at', $month);
            },
            'reservations as rejected_count' => function ($q) use ($month, $year) {
                $q->where('status', 'Rejected')->whereYear('created_at', $year);
                if ($month) $q->whereMonth('created_at', $month);
            },
            'reservations as total_requests' => function ($q) use ($month, $year) {
                $q->whereYear('created_at', $year);
                if ($month) $q->whereMonth('created_at', $month);
            },
        ])->get();

        $this->totalRooms = 0;
        $this->totalRevenue = 0;
        $this->totalLostRevenue = 0;

        foreach ($this->rooms as $room) {
            $room->revenue = $room->approved_count * $room->room_rate;
            $room->lost_revenue = $room->rejected_count * $room->room_rate;

            $this->totalRooms++;
            $this->totalRevenue += $room->revenue;
            $this->totalLostRevenue += $room->lost_revenue;
        }
    }
}
