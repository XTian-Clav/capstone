<?php

namespace App\Filament\Portal\Resources\ReserveEquipment\Pages;

use App\Models\ReserveEquipment;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use App\Filament\Portal\Resources\ReserveEquipment\ReserveEquipmentResource;

class ListReserveEquipment extends ListRecords
{
    protected static string $resource = ReserveEquipmentResource::class;

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
                ->badge(fn () => ReserveEquipment::count()),

            'pending' => Tab::make('Pending')
                ->badge(fn () => ReserveEquipment::where('status', 'pending')->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'pending')),

            'approved' => Tab::make('Approved')
                ->badge(fn () => ReserveEquipment::where('status', 'approved')->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'approved')),

            'rejected' => Tab::make('Rejected')
                ->badge(fn () => ReserveEquipment::where('status', 'rejected')->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'rejected')),

            'archived' => Tab::make('Archive')
                ->badge(fn () => ReserveEquipment::onlyTrashed()->count())
                ->modifyQueryUsing(fn ($query) => $query->onlyTrashed()),
        ];
    }
}
