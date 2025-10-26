<?php

namespace App\Filament\Portal\Widgets;

use App\Models\User;
use App\Models\Event;
use App\Models\Mentor;
use App\Models\Startup;
use App\Models\ReserveRoom;
use App\Models\ReserveSupply;
use App\Models\ReserveEquipment;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AnalyticsStats extends StatsOverviewWidget
{
    public static function canView(): bool
    {
        $user = auth()->user();
        return $user->hasAnyRole(['admin', 'super_admin']);
    }

    protected ?string $pollingInterval = '60s';
    protected static ?int $sort = 1;
    
    protected function getStats(): array
    {
        return [
            Stat::make('Total Startups', Startup::count())
                ->description('Total Startups submission')
                ->descriptionIcon('heroicon-s-briefcase', IconPosition::Before)
                ->color('violet'),

            Stat::make('Total Events', Event::count())
                ->description('Total Events held by Pitbi')
                ->descriptionIcon('heroicon-s-calendar-days', IconPosition::Before)
                ->color('violet'),

            Stat::make(
                'Total Reservations',
                collect([
                    //ReserveEquipment::where('status', 'pending')->count(),
                    ReserveEquipment::count(),
                    ReserveRoom::count(),
                    ReserveSupply::count(),
                ])->sum()
            )
                ->description('Total reservations requests')
                ->descriptionIcon('heroicon-s-clipboard-document-list', IconPosition::Before)
                ->color('violet'),
    ];
    }
}
