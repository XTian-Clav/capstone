<?php

namespace App\Filament\Portal\Resources\Events\Pages;

use App\Models\Event;
use Filament\Actions\CreateAction;
use Illuminate\Support\Collection;
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
        $counts = $this->cachedCounts ??= collect([
            'all' => Event::count(),
            'Upcoming' => Event::where('status', 'Upcoming')->count(),
            'Ongoing' => Event::where('status', 'Ongoing')->count(),
            'Completed' => Event::where('status', 'Completed')->count(),
            'Cancelled' => Event::where('status', 'Cancelled')->count(),
            'archived' => Event::onlyTrashed()->count(),
        ]);

        return [
            'all' => Tab::make('All')
                ->badge($counts['all']),

            'Upcoming' => Tab::make('Upcoming')
                ->badge($counts['Upcoming'])
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'Upcoming')),

            'Ongoing' => Tab::make('Ongoing')
                ->badge($counts['Ongoing'])
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'Ongoing')),

            'Completed' => Tab::make('Completed')
                ->badge($counts['Completed'])
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'Completed')),

            'Cancelled' => Tab::make('Cancelled')
                ->badge($counts['Cancelled'])
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'Cancelled')),

            'archived' => Tab::make('Archive')
                ->badge(fn () => Event::onlyTrashed()->count())
                ->modifyQueryUsing(fn ($query) => $query->onlyTrashed()),
        ];
    }
}
