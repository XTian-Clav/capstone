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
                ->description('Total Incubatees registered to Pitbi')
                ->descriptionIcon('heroicon-m-user-group', IconPosition::Before)
                ->chart([1,5,9])
                ->color('success'),
            
            Stat::make('Total Investors', User::whereHas('roles', fn ($q) => $q->where('name', 'investor'))->count())
                ->description('Total Investor registered to Pitbi')
                ->descriptionIcon('heroicon-m-user-group', IconPosition::Before)
                ->chart([1,5,9])
                ->color('success'),
            
            Stat::make('Total Mentors', Mentor::count())
                ->description('Total Mentors registered to Pitbi')
                ->descriptionIcon('heroicon-m-user-group', IconPosition::Before)
                ->chart([1,5,9])
                ->color('success'),

            Stat::make('Total Startups', Startup::count())
                ->description('Total Startups registered to Pitbi')
                ->descriptionIcon('heroicon-m-user-group', IconPosition::Before)
                ->chart([1,5,9])
                ->color('success'),

            Stat::make('Total Events', Event::count())
                ->description('Total Events held by Pitbi')
                ->descriptionIcon('heroicon-m-user-group', IconPosition::Before)
                ->chart([1,5,9])
                ->color('success'),

            Stat::make(
                'Total Reservations',
                collect([
                    //ReserveEquipment::where('status', 'pending')->count(),
                    ReserveEquipment::count(),
                    ReserveRoom::count(),
                    ReserveSupply::count(),
                ])->sum()
            )
                ->description('Total pending reservations')
                ->descriptionIcon('heroicon-m-clock', IconPosition::Before)
                ->chart([1, 5, 9])
                ->color('success'),
    ];
    }
}
