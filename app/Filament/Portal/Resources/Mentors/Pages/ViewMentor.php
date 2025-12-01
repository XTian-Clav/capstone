<?php

namespace App\Filament\Portal\Resources\Mentors\Pages;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use App\Filament\Actions\BackButton;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Portal\Resources\Mentors\MentorResource;

class ViewMentor extends ViewRecord
{
    protected static string $resource = MentorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            BackButton::make(),
            EditAction::make(),
        ];
    }
}
