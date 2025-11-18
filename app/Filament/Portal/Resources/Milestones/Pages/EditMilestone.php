<?php

namespace App\Filament\Portal\Resources\Milestones\Pages;

use App\Models\User;
use App\Models\Startup;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Portal\Resources\Milestones\MilestoneResource;

class EditMilestone extends EditRecord
{
    protected static string $resource = MilestoneResource::class;

    protected function getHeaderActions(): array
    {
        $user = auth()->user();

        return [
            ViewAction::make()->visible(fn () => $user->hasAnyRole(['admin', 'super_admin'])),
            DeleteAction::make()->visible(fn () => $user->hasAnyRole(['admin', 'super_admin'])),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $record = $this->record;
        
        $previous = (bool) ($record->is_done ?? false);
        $new = (bool) ($data['is_done'] ?? $previous);

        if (! $previous && $new) {
            $admin = auth()->user();
            
            $startup = Startup::find($record->startup_id ?? $data['startup_id'] ?? null);
            $owner = $startup ? User::find($startup->user_id) : null;

            if ($owner) {
                $milestoneTitle = $record->title ?? ($data['title'] ?? 'your milestone');

                Notification::make()
                    ->title('Milestone Completed')
                    ->body('Your milestone "' . $milestoneTitle . '" has been marked as done.')
                    ->sendToDatabase($owner);

                
                Notification::make()
                    ->title('Milestone Marked as Done')
                    ->body('You have marked "' . $milestoneTitle . '" as completed for ' . $owner->name . '.')
                    ->sendToDatabase($admin);
            }
        }

        return parent::mutateFormDataBeforeSave($data);
    }
}
