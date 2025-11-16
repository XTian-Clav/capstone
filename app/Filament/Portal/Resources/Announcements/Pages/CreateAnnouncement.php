<?php

namespace App\Filament\Portal\Resources\Announcements\Pages;

use App\Filament\Portal\Resources\Announcements\AnnouncementResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAnnouncement extends CreateRecord
{
    protected static string $resource = AnnouncementResource::class;
}
