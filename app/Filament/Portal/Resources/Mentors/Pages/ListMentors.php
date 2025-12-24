<?php

namespace App\Filament\Portal\Resources\Mentors\Pages;

use App\Models\Mentor;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Asmit\ResizedColumn\HasResizableColumn;
use App\Filament\Portal\Widgets\MentorWidget;
use App\Filament\Portal\Resources\Mentors\MentorResource;

class ListMentors extends ListRecords
{
    protected static string $resource = MentorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Create Mentor')->icon('heroicon-o-plus'),
        ];
    }

    public function getTabs(): array
    {
        $user = auth()->user();

        // Hide all tabs for non-superadmin users
        if (! $user->hasRole('super_admin')) {
            return [];
        }
        
        return [
            'all' => Tab::make('All')
                ->badge(fn () => Mentor::count()),

            'archived' => Tab::make('Archive')
                ->badge(fn () => Mentor::onlyTrashed()->count())
                ->modifyQueryUsing(fn ($query) => $query->onlyTrashed()),
        ];
    }
}
