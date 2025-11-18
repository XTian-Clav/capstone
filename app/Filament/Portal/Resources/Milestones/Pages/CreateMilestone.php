<?php

namespace App\Filament\Portal\Resources\Milestones\Pages;

use App\Models\User;
use App\Models\Startup;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Portal\Resources\Milestones\MilestoneResource;

class CreateMilestone extends CreateRecord
{
    protected static string $resource = MilestoneResource::class;

    protected function afterCreate(): void
    {
        $milestone = $this->record;
        $startup = Startup::find($milestone->startup_id);
        $owner = $startup ? User::find($startup->user_id) : null;

        if (! $owner) {
            return;
        }

        $body = strip_tags($milestone->description ?? $milestone->summary ?? '');
        $body = $body ?: 'A new milestone was added to your startup.';

        Notification::make()
            ->title('New Milestone: ' . ($milestone->title ?? 'Untitled'))
            ->body(Str::limit($body, 300))
            ->sendToDatabase($owner);
        
        if ($admin = auth()->user()) {
            Notification::make()
                ->title('Milestone Created')
                ->body('You created "' . ($milestone->title ?? 'a milestone') . '" for ' . ($startup->startup_name ?? 'the startup') . '.')
                ->sendToDatabase($admin);
        }
    }
}
