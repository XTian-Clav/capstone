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
    protected function getStats(): array
    {
        return [
            Stat::make('Total Icubatees', User::whereHas('roles', fn ($q) => $q->where('name', 'incubatee'))->count())
                ->description('Total Incubatees registered')
                ->descriptionIcon('heroicon-s-user-group', IconPosition::Before)
                ->color('primary'),
            
            Stat::make('Total Investors', User::whereHas('roles', fn ($q) => $q->where('name', 'investor'))->count())
                ->description('Total Investor registered')
                ->descriptionIcon('heroicon-s-banknotes', IconPosition::Before)
                ->color('secondary'),
            
            Stat::make('Total Mentors', Mentor::count())
                ->description('Total Mentors registered')
                ->descriptionIcon('heroicon-s-academic-cap', IconPosition::Before)
                ->color('success'),

            Stat::make('Total Startups', Startup::count())
                ->description('Total Startups submission')
                ->descriptionIcon('heroicon-s-briefcase', IconPosition::Before)
                ->color('danger'),

            Stat::make('Total Events', Event::count())
                ->description('Total Events held by Pitbi')
                ->descriptionIcon('heroicon-s-calendar-days', IconPosition::Before)
                ->color('primary'),

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
                ->descriptionIcon('heroicon-s-clock', IconPosition::Before)
                ->color('secondary'),
    ];
    }
}
