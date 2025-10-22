<?php

namespace App\Filament\Portal\Widgets;

use Carbon\Carbon;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class WelcomeCard extends StatsOverviewWidget
{
    // Optional: update every minute if you want the time to refresh automatically
    protected ?string $pollingInterval = '60s';
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $user = auth()->user();
        $hour = now()->hour;

        $greeting = match (true) {
            $hour < 12 => 'Good morning',
            $hour < 18 => 'Good afternoon',
            default => 'Good evening',
        };

        $date = Carbon::now()->format('F j, Y');
        $day = Carbon::now()->format('l');
        $time = Carbon::now()->format('h:i A');

        return [
            // LEFT CARD — Greeting + Date
            Stat::make("{$greeting}, {$user->name}!", $date)
                ->description('Welcome to the PITBI Portal — wishing you a productive day ahead!')
                ->descriptionIcon('heroicon-s-sparkles')
                ->color('primary')
                ->columnSpan(2),

            // RIGHT CARD — Day + Time
            Stat::make($day, $time)
                ->description('Current day and time')
                ->descriptionIcon('heroicon-s-clock', IconPosition::Before)
                ->color('primary')
                ->columnSpan(1),
        ];
    }
}
