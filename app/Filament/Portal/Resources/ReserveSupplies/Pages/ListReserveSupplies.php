<?php

namespace App\Filament\Portal\Resources\ReserveSupplies\Pages;

use App\Models\ReserveSupply;
use Filament\Actions\CreateAction;
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
        return [
            'all' => Tab::make('All')
                ->badge(fn () => ReserveSupply::count()),

            'pending' => Tab::make('Pending')
                ->badge(fn () => ReserveSupply::where('status', 'pending')->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'pending')),

            'approved' => Tab::make('Approved')
                ->badge(fn () => ReserveSupply::where('status', 'approved')->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'approved')),

            'rejected' => Tab::make('Rejected')
                ->badge(fn () => ReserveSupply::where('status', 'rejected')->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'rejected')),

            'archived' => Tab::make('Archive')
                ->badge(fn () => ReserveSupply::onlyTrashed()->count())
                ->modifyQueryUsing(fn ($query) => $query->onlyTrashed()),
        ];
    }
}
