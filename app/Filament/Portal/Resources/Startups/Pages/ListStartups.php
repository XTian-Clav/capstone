<?php

namespace App\Filament\Portal\Resources\Startups\Pages;

use App\Models\Startup;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use App\Filament\Portal\Resources\Startups\StartupResource;

class ListStartups extends ListRecords
{
    protected static string $resource = StartupResource::class;

    protected ?Collection $cachedCounts = null;

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
                ->badge(fn () => Startup::count()),

            'pending' => Tab::make('Pending')
                ->badge(fn () => Startup::where('status', 'pending')->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'pending')),

            'approved' => Tab::make('Approved')
                ->badge(fn () => Startup::where('status', 'approved')->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'approved')),

            'rejected' => Tab::make('Rejected')
                ->badge(fn () => Startup::where('status', 'rejected')->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'rejected')),

            'archived' => Tab::make('Archive')
                ->badge(fn () => Startup::onlyTrashed()->count())
                ->modifyQueryUsing(fn ($query) => $query->onlyTrashed()),
        ];
    }
}
