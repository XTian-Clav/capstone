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
    use HasResizableColumn;
    
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
            'all' => Tab::make('All')
                ->badge(fn () => $isAdmin ? ReserveSupply::count() : ReserveSupply::where('reserved_by', $user->name)->count()),

            'pending' => Tab::make('Pending')
                ->badge(fn () => $isAdmin 
                    ? ReserveSupply::where('status', 'pending')->count() 
                    : ReserveSupply::where('status', 'pending')->where('reserved_by', $user->name)->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'pending')),

            'approved' => Tab::make('Approved')
                ->badge(fn () => $isAdmin 
                    ? ReserveSupply::where('status', 'approved')->count()
                    : ReserveSupply::where('status', 'approved')->where('reserved_by', $user->name)->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'approved')),

            'rejected' => Tab::make('Rejected')
                ->badge(fn () => $isAdmin 
                    ? ReserveSupply::where('status', 'rejected')->count()
                    : ReserveSupply::where('status', 'rejected')->where('reserved_by', $user->name)->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'rejected')),
        ];
        // Add archive tab only for SuperAdmin
        if ($user->hasRole('super_admin')) {
            $tabs['archived'] = Tab::make('Archive')
                ->badge(fn () => ReserveSupply::onlyTrashed()->count())
                ->modifyQueryUsing(fn ($query) => $query->onlyTrashed());
        }

        return $tabs;
    }
}
