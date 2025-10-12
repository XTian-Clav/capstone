<?php

namespace App\Filament\Resources\Startups\Pages;

use App\Filament\Resources\Startups\StartupResource;
use App\Models\Startup;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Support\Collection;

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
        ];
    }
}
