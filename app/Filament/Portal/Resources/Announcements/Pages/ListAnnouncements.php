<?php

namespace App\Filament\Portal\Resources\Announcements\Pages;

use App\Filament\Portal\Resources\Announcements\AnnouncementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAnnouncements extends ListRecords
{
    protected static string $resource = AnnouncementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Create Announcement')->icon('heroicon-o-plus'),
        ];
    }
}
