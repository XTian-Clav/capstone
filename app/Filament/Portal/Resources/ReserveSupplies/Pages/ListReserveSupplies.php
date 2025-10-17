<?php

namespace App\Filament\Portal\Resources\ReserveSupplies\Pages;

use App\Models\ReserveSupply;
use Filament\Actions\CreateAction;
use Illuminate\Support\Collection;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use App\Filament\Portal\Resources\ReserveSupplies\ReserveSupplyResource;

class ListReserveSupplies extends ListRecords
{
    protected static string $resource = ReserveSupplyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $counts = $this->cachedCounts ??= collect([
            'all' => ReserveSupply::count(),
            'pending' => ReserveSupply::where('status', 'pending')->count(),
            'approved' => ReserveSupply::where('status', 'approved')->count(),
            'rejected' => ReserveSupply::where('status', 'rejected')->count(),
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
