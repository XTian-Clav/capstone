<?php

namespace App\Filament\Portal\Pages;

use UnitEnum;
use App\Models\Room;
use Filament\Pages\Page;
use Filament\Actions\Action;

class RoomReport extends Page
{
    protected static ?int $navigationSort = 1;
    protected static string|UnitEnum|null $navigationGroup = 'Generate Reports';

    protected string $view = 'filament.portal.pages.room-report';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'super_admin']);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print_report')
                ->label('Print')
                ->icon('heroicon-s-printer')
                ->color('info')
                ->url(route('RoomReport'))
                ->openUrlInNewTab(),
        ];
    }

    public $rooms;
    public $totalRooms = 0;
    public $totalRevenue = 0;
    public $totalLostRevenue = 0;

    public function mount(): void
    {
        $this->rooms = Room::with('reservations')->get();

        $this->rooms->transform(function ($room) {
            $all = $room->reservations;
            
            $room->approved_count = $all->where('status', 'Approved')->count();
            $room->rejected_count = $all->where('status', 'Rejected')->count();
            $room->total_requests = $all->count();
            
            $room->revenue = $room->approved_count * $room->room_rate;
            $room->lost_revenue = $room->rejected_count * $room->room_rate;

            $this->totalRooms++;
            $this->totalRevenue += $room->revenue;
            $this->totalLostRevenue += $room->lost_revenue;

            return $room;
        });
    }
}
