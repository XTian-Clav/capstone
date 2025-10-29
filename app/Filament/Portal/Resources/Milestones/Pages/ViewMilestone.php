<?php

namespace App\Filament\Portal\Resources\Milestones\Pages;

use App\Filament\Portal\Resources\Milestones\MilestoneResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMilestone extends ViewRecord
{
    protected static string $resource = MilestoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
            ->label(fn () => auth()->user()->hasAnyRole(['admin', 'super_admin']) 
                ? 'Edit Task' 
                : 'Comply Task'),
        ];
    }
}
