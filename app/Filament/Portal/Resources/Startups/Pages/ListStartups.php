<?php

namespace App\Filament\Portal\Resources\Startups\Pages;

use App\Models\Startup;
use Filament\Actions\CreateAction;
use Illuminate\Support\Collection;
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
        $counts = $this->cachedCounts ??= collect([
            'all' => Startup::count(),
            'pending' => Startup::where('status', 'pending')->count(),
            'approved' => Startup::where('status', 'approved')->count(),
            'rejected' => Startup::where('status', 'rejected')->count(),
            'archived' => Startup::onlyTrashed()->count(),
        ]);

        return [
            'all' => Tab::make('All')
                ->badge($counts['all']),

            'pending' => Tab::make('Pending')
                ->badge($counts['pending'])
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'pending')),

            'approved' => Tab::make('Approved')
                ->badge($counts['approved'])
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'approved')),

            'rejected' => Tab::make('Rejected')
                ->badge($counts['rejected'])
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'rejected')),
                
            'archived' => Tab::make('Archive')
                ->badge(fn () => Startup::onlyTrashed()->count())
                ->modifyQueryUsing(fn ($query) => $query->onlyTrashed()),
        ];
    }
}
