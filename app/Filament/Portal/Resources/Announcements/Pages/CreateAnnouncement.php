<?php

namespace App\Filament\Portal\Resources\Announcements\Pages;

use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Portal\Resources\Announcements\AnnouncementResource;

class CreateAnnouncement extends CreateRecord
{
    protected static string $resource = AnnouncementResource::class;

    protected function afterCreate(): void
    {
        $announcement = $this->record;
        
        $users = User::all();

        foreach ($users as $user) {
            Notification::make()
                ->title('New Announcement: ' . $announcement->title)
                ->body($announcement->content)
                ->sendToDatabase($user);
        }
    }
}
