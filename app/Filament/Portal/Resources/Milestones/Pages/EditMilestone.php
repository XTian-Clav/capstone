<?php

namespace App\Filament\Portal\Resources\Milestones\Pages;

use App\Filament\Portal\Resources\Milestones\MilestoneResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

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
}
