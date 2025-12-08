<?php

namespace App\Filament\Portal\Resources\Events\Pages;

use App\Models\Event;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Asmit\ResizedColumn\HasResizableColumn;
use App\Filament\Portal\Resources\Events\EventResource;

class ListEvents extends ListRecords
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        $user = auth()->user();
        
        return [
            CreateAction::make()->visible(fn () => $user->hasAnyRole(['admin', 'super_admin'])),
        ];
    }

    public function getTabs(): array
    {
        $user = auth()->user();
        
        $tabs = [
            'Upcoming' => Tab::make('Upcoming')
                ->badge(fn () => Event::where('status', 'Upcoming')->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'Upcoming')),

            'Ongoing' => Tab::make('Ongoing')
                ->badge(fn () => Event::where('status', 'Ongoing')->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'Ongoing')),

            'Completed' => Tab::make('Completed')
                ->badge(fn () => Event::where('status', 'Completed')->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'Completed')),

            'Cancelled' => Tab::make('Cancelled')
                ->badge(fn () => Event::where('status', 'Cancelled')->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'Cancelled')),

            'all' => Tab::make('All')
                ->badge(fn () => Event::count()),
        ];
        // Add archive tab only for SuperAdmin
        if ($user->hasRole('super_admin')) {
            $tabs['archived'] = Tab::make('Archive')
                ->badge(fn () => Event::onlyTrashed()->count())
                ->modifyQueryUsing(fn ($query) => $query->onlyTrashed());
        }

        return $tabs;
    }
}
