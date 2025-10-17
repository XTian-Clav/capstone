<?php

namespace App\Filament\Portal\Resources\ReserveEquipment\Pages;

use App\Models\ReserveEquipment;
use Filament\Actions\CreateAction;
use Illuminate\Support\Collection;
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
        $counts = $this->cachedCounts ??= collect([
            'all' => ReserveEquipment::count(),
            'pending' => ReserveEquipment::where('status', 'pending')->count(),
            'approved' => ReserveEquipment::where('status', 'approved')->count(),
            'rejected' => ReserveEquipment::where('status', 'rejected')->count(),
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
