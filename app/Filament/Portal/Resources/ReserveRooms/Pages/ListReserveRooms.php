<?php

namespace App\Filament\Portal\Resources\ReserveRooms\Pages;

use App\Models\ReserveRoom;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Asmit\ResizedColumn\HasResizableColumn;
use App\Filament\Actions\Room\ApprovedScheduleAction;
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
        $isAdmin = $user->hasAnyRole(['admin', 'super_admin']);

        $tabs = [
            'pending' => Tab::make('Pending')
                ->badge(fn () => $isAdmin 
                    ? ReserveRoom::where('status', 'pending')->count() 
                    : ReserveRoom::where('status', 'pending')->where('user_id', $user->id)->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'pending')),

            'approved' => Tab::make('Approved')
                ->badge(fn () => $isAdmin 
                    ? ReserveRoom::where('status', 'approved')->count()
                    : ReserveRoom::where('status', 'approved')->where('user_id', $user->id)->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'approved')),

            'rejected' => Tab::make('Rejected')
                ->badge(fn () => $isAdmin 
                    ? ReserveRoom::where('status', 'rejected')->count()
                    : ReserveRoom::where('status', 'rejected')->where('user_id', $user->id)->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'rejected')),

            'completed' => Tab::make('Completed')
                ->badge(fn () => $isAdmin 
                    ? ReserveRoom::where('status', 'completed')->count()
                    : ReserveRoom::where('status', 'completed')->where('user_id', $user->id)->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'completed')),

            'all' => Tab::make('All')
                ->badge(fn () => ReserveRoom::count()),
        ];

        return $tabs;
    }
}
