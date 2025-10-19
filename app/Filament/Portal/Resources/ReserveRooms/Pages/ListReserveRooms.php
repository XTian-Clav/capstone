<?php

namespace App\Filament\Portal\Resources\ReserveRooms\Pages;

use App\Models\ReserveRoom;
use Filament\Actions\CreateAction;
use Illuminate\Support\Collection;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use App\Filament\Portal\Resources\ReserveRooms\ReserveRoomResource;

class ListReserveRooms extends ListRecords
{
    protected static string $resource = ReserveRoomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $counts = $this->cachedCounts ??= collect([
            'all' => ReserveRoom::count(),
            'pending' => ReserveRoom::where('status', 'pending')->count(),
            'approved' => ReserveRoom::where('status', 'approved')->count(),
            'rejected' => ReserveRoom::where('status', 'rejected')->count(),
            'archived' => ReserveRoom::onlyTrashed()->count(),
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
                ->badge(fn () => ReserveRoom::onlyTrashed()->count())
                ->modifyQueryUsing(fn ($query) => $query->onlyTrashed()),
        ];
    }
}
