<?php

namespace App\Filament\Portal\Resources\ReserveEquipment\Pages;

use App\Models\ReserveEquipment;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Asmit\ResizedColumn\HasResizableColumn;
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
        $user = auth()->user();
        $isAdmin = $user->hasAnyRole(['admin', 'super_admin']);

        $tabs = [
            'all' => Tab::make('All')
                ->badge(fn () => $isAdmin ? ReserveEquipment::count() : ReserveEquipment::where('user_id', $user->id)->count()),

            'pending' => Tab::make('Pending')
                ->badge(fn () => $isAdmin 
                    ? ReserveEquipment::where('status', 'pending')->count() 
                    : ReserveEquipment::where('status', 'pending')->where('user_id', $user->id)->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'pending')),

            'approved' => Tab::make('Approved')
                ->badge(fn () => $isAdmin 
                    ? ReserveEquipment::where('status', 'approved')->count()
                    : ReserveEquipment::where('status', 'approved')->where('user_id', $user->id)->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'approved')),

            'rejected' => Tab::make('Rejected')
                ->badge(fn () => $isAdmin 
                    ? ReserveEquipment::where('status', 'rejected')->count()
                    : ReserveEquipment::where('status', 'rejected')->where('user_id', $user->id)->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'rejected')),
        ];
        // Add archive tab only for SuperAdmin
        if ($user->hasRole('super_admin')) {
            $tabs['archived'] = Tab::make('Archive')
                ->badge(fn () => ReserveEquipment::onlyTrashed()->count())
                ->modifyQueryUsing(fn ($query) => $query->onlyTrashed());
        }

        return $tabs;
    }
}
