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
    use HasResizableColumn;
    
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
        $isAdmin = $user->hasAnyRole(['admin', 'super_admin', 'investor']);

        $tabs = [
            'all' => Tab::make('All')
                ->badge(fn () => $isAdmin ? Startup::count() : Startup::where('user_id', $user->id)->count()),

            'pending' => Tab::make('Pending')
                ->badge(fn () => $isAdmin 
                    ? Startup::where('status', 'pending')->count() 
                    : Startup::where('status', 'pending')->where('user_id', $user->id)->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'pending')),

            'approved' => Tab::make('Approved')
                ->badge(fn () => $isAdmin 
                    ? Startup::where('status', 'approved')->count()
                    : Startup::where('status', 'approved')->where('user_id', $user->id)->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'approved')),

            'rejected' => Tab::make('Rejected')
                ->badge(fn () => $isAdmin 
                    ? Startup::where('status', 'rejected')->count()
                    : Startup::where('status', 'rejected')->where('user_id', $user->id)->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'rejected')),
        ];
        // Add archive tab only for SuperAdmin
        if ($user->hasRole('super_admin')) {
            $tabs['archived'] = Tab::make('Archive')
                ->badge(fn () => Startup::onlyTrashed()->count())
                ->modifyQueryUsing(fn ($query) => $query->onlyTrashed());
        }

        return $tabs;
    }
}
