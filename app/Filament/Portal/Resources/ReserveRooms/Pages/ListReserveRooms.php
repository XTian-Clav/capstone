<?php

namespace App\Filament\Portal\Resources\ReserveRooms\Pages;

use App\Models\ReserveRoom;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Asmit\ResizedColumn\HasResizableColumn;
use App\Filament\Portal\Resources\ReserveRooms\ReserveRoomResource;

class ListReserveRooms extends ListRecords
{
    use HasResizableColumn;
    
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
        $isAdmin = $user->hasAnyRole(['admin', 'super_admin']);

        $tabs = [
            'all' => Tab::make('All')
                ->badge(fn () => $isAdmin ? ReserveRoom::count() : ReserveRoom::where('reserved_by', $user->name)->count()),

            'pending' => Tab::make('Pending')
                ->badge(fn () => $isAdmin 
                    ? ReserveRoom::where('status', 'pending')->count() 
                    : ReserveRoom::where('status', 'pending')->where('reserved_by', $user->name)->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'pending')),

            'approved' => Tab::make('Approved')
                ->badge(fn () => $isAdmin 
                    ? ReserveRoom::where('status', 'approved')->count()
                    : ReserveRoom::where('status', 'approved')->where('reserved_by', $user->name)->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'approved')),

            'rejected' => Tab::make('Rejected')
                ->badge(fn () => $isAdmin 
                    ? ReserveRoom::where('status', 'rejected')->count()
                    : ReserveRoom::where('status', 'rejected')->where('reserved_by', $user->name)->count())
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
