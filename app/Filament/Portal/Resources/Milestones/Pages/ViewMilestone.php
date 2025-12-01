<?php

namespace App\Filament\Portal\Resources\Milestones\Pages;

use Filament\Actions\EditAction;
use App\Filament\Actions\BackButton;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Portal\Resources\Milestones\MilestoneResource;

class ViewMilestone extends ViewRecord
{
    protected static string $resource = MilestoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            BackButton::make(),
            EditAction::make()
                ->label(fn () => auth()->user()->hasAnyRole(['admin', 'super_admin']) ? 'Edit Task' : 'Comply Task'),
        ];
    }
}
