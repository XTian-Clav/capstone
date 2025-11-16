<?php

namespace App\Filament\Portal\Widgets;

use App\Models\Announcement;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AnnouncementWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 7;

    public static function canView(): bool
    {
        $user = auth()->user();
        return ! $user->hasAnyRole(['admin', 'super_admin']);
    }

    protected function getStats(): array
    {
        $latest = Announcement::latest()->first();

        if (! $latest) {
            return [
                Stat::make('No announcements', '')
                    ->description('There are currently no announcements.')
                    ->descriptionIcon('heroicon-o-megaphone',  IconPosition::Before)
                    ->columnSpan(2),

                Stat::make('Published at:', '')
                    ->description('No announcement yet')
                    ->descriptionIcon('heroicon-o-calendar',  IconPosition::Before)
                    ->columnSpan(1),
            ];
        }

        return [
            Stat::make($latest->title, '')
                ->description($latest->content)
                ->descriptionIcon('heroicon-o-megaphone',  IconPosition::Before)
                ->columnSpan(2)
                ->color('success'),

            Stat::make("Published at:", '')
                ->description($latest->created_at->format('M d, Y g:i A'))
                ->descriptionIcon('heroicon-o-calendar',  IconPosition::Before)
                ->columnSpan(1)
                ->color('success'),
        ];
    }
}
