<?php

namespace App\Filament\Portal\Resources\ReserveRooms\Pages;

use App\Models\ReserveRoom;
use Filament\Actions\CreateAction;
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
        $user = auth()->user();
        
        $tabs = [
            'all' => Tab::make('All')
                ->badge(fn () => ReserveRoom::count()),

            'pending' => Tab::make('Pending')
                ->badge(fn () => ReserveRoom::where('status', 'pending')->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'pending')),

            'approved' => Tab::make('Approved')
                ->badge(fn () => ReserveRoom::where('status', 'approved')->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'approved')),

            'rejected' => Tab::make('Rejected')
                ->badge(fn () => ReserveRoom::where('status', 'rejected')->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'rejected')),
        ];
        // Add archive tab only for SuperAdmin
        if ($user->hasRole('super_admin')) {
            $tabs['archived'] = Tab::make('Archive')
                ->badge(fn () => ReserveRoom::onlyTrashed()->count())
                ->modifyQueryUsing(fn ($query) => $query->onlyTrashed());
        }

        return $tabs;
    }
}
