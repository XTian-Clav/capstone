<?php

namespace App\Filament\Portal\Resources\ReserveSupplies\Pages;

use App\Models\ReserveSupply;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Asmit\ResizedColumn\HasResizableColumn;
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
        $user = auth()->user();
        $isAdmin = $user->hasAnyRole(['admin', 'super_admin']);

        $tabs = [
            'pending' => Tab::make('Pending')
                ->badge(fn () => $isAdmin 
                    ? ReserveSupply::where('status', 'pending')->count() 
                    : ReserveSupply::where('status', 'pending')->where('user_id', $user->id)->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'pending')),

            'approved' => Tab::make('Approved')
                ->badge(fn () => $isAdmin 
                    ? ReserveSupply::where('status', 'approved')->count()
                    : ReserveSupply::where('status', 'approved')->where('user_id', $user->id)->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'approved')),

            'rejected' => Tab::make('Rejected')
                ->badge(fn () => $isAdmin 
                    ? ReserveSupply::where('status', 'rejected')->count()
                    : ReserveSupply::where('status', 'rejected')->where('user_id', $user->id)->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'rejected')),

            'completed' => Tab::make('Completed')
                ->badge(fn () => $isAdmin 
                    ? ReserveSupply::where('status', 'completed')->count()
                    : ReserveSupply::where('status', 'completed')->where('user_id', $user->id)->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'completed')),
        ];

        return $tabs;
    }
}
