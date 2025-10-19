<?php

namespace App\Filament\Portal\Resources\Events\Pages;

use App\Models\Event;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use App\Filament\Portal\Resources\Events\EventResource;

class ListEvents extends ListRecords
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->badge(fn () => Event::count()),

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

            'archived' => Tab::make('Archive')
                ->badge(fn () => Event::onlyTrashed()->count())
                ->modifyQueryUsing(fn ($query) => $query->onlyTrashed()),
        ];
    }
}
