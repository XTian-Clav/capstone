<?php

namespace App\Filament\Portal\Resources\Startups\Pages;

use App\Models\Startup;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Asmit\ResizedColumn\HasResizableColumn;
use App\Filament\Portal\Widgets\StartupWidget;
use App\Filament\Portal\Resources\Startups\StartupResource;

class ListStartups extends ListRecords
{
    protected static string $resource = StartupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $user = auth()->user();

        $isAdmin = $user->hasRole('admin');
        $isSuperAdmin = $user->hasRole('super_admin');
        $isInvestor = $user->hasRole('investor');
        $isIncubatee = $user->hasRole('incubatee');

        if ($isInvestor) {
            return [
                'approved' => Tab::make('Approved')
                    ->badge(fn () => Startup::where('status', 'approved')->count())
                    ->modifyQueryUsing(fn ($query) => $query->where('status', 'approved')),
            ];
        }

        $tabs = [
            'pending' => Tab::make('Pending')
                ->badge(fn () =>
                    ($isAdmin || $isSuperAdmin)
                        ? Startup::where('status', 'pending')->count()
                        : Startup::where('status', 'pending')->where('user_id', $user->id)->count()
                )
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'pending')),

            'approved' => Tab::make('Approved')
                ->badge(fn () =>
                    ($isAdmin || $isSuperAdmin)
                        ? Startup::where('status', 'approved')->count()
                        : Startup::where('status', 'approved')->where('user_id', $user->id)->count()
                )
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'approved')),

            'rejected' => Tab::make('Rejected')
                ->badge(fn () =>
                    ($isAdmin || $isSuperAdmin)
                        ? Startup::where('status', 'rejected')->count()
                        : Startup::where('status', 'rejected')->where('user_id', $user->id)->count()
                )
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'rejected')),
        ];
        
        if ($isSuperAdmin) {
            $tabs['archived'] = Tab::make('Archive')
                ->badge(fn () => Startup::onlyTrashed()->count())
                ->modifyQueryUsing(fn ($query) => $query->onlyTrashed());
        }

        return $tabs;
    }
}
