<?php

namespace App\Filament\Actions\Room; // Updated Namespace

use App\Models\ReserveRoom;
use Filament\Actions\Action;
use Illuminate\Contracts\View\View;

class ApprovedScheduleAction extends Action // Updated Class Name
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'view_approved_schedule')
            ->button()
            ->color('gray')
            ->label('View Schedules')
            ->icon('heroicon-o-calendar-days')
            ->modalWidth('4xl')
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Close Schedule')
            ->modalHeading('Approved Room Schedule')
            ->modalContent(function (): View {
                $approvedReservations = ReserveRoom::query()
                    ->where('status', 'Approved')
                    ->where('end_date', '>=', now())
                    ->with(['room', 'user'])
                    ->orderBy('start_date', 'asc')
                    ->get();
                return view('filament.pages.approved-room-schedule', [ 
                    'approvedReservations' => $approvedReservations,
                ]);
            });
    }
}