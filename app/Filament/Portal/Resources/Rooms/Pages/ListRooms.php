<?php

namespace App\Filament\Portal\Resources\Rooms\Pages;

use App\Models\Room;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Asmit\ResizedColumn\HasResizableColumn;
use App\Filament\Portal\Resources\Rooms\RoomResource;

class ListRooms extends ListRecords
{
    use HasResizableColumn;
    
    protected static string $resource = RoomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $user = auth()->user();

        // Hide all tabs for non-superadmin users
        if (! $user->hasRole('super_admin')) {
            return [];
        }
        
        return [
            'all' => Tab::make('All')
                ->badge(fn () => Room::count()),

            'archived' => Tab::make('Archive')
                ->badge(fn () => Room::onlyTrashed()->count())
                ->modifyQueryUsing(fn ($query) => $query->onlyTrashed()),
        ];
    }
}
