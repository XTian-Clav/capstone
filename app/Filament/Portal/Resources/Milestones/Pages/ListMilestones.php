<?php

namespace App\Filament\Portal\Resources\Milestones\Pages;

use App\Filament\Portal\Resources\Milestones\MilestoneResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMilestones extends ListRecords
{
    protected static string $resource = MilestoneResource::class;

    protected function getHeaderActions(): array
    {
        $user = auth()->user();
        
        return [
            CreateAction::make()->visible(fn () => $user->hasAnyRole(['admin', 'super_admin'])),
        ];
    }
}
