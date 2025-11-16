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
        return auth()->user()->hasAnyRole(['admin', 'super_admin']);
    }

    protected function getStats(): array
    {
        $latest = Announcement::latest()->first();

        if (! $latest) {
            return [
                Stat::make('No announcements', '—')
                    ->description('There are currently no announcements.')
                    ->descriptionIcon('heroicon-o-megaphone',  IconPosition::Before)
                    ->columnSpanFull(),
            ];
        }

        return [
            Stat::make("Announcement: {$latest->title}", '')
                ->description($latest->content)
                ->descriptionIcon('heroicon-o-megaphone',  IconPosition::Before)
                ->columnSpanFull()
                ->color('success'),
        ];
    }
}
