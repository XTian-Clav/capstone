<?php

namespace App\Filament\Portal\Resources\Startups\Pages;

use App\Models\Startup;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Asmit\ResizedColumn\HasResizableColumn;
use App\Filament\Portal\Resources\Startups\StartupResource;

class ListStartups extends ListRecords
{
    use HasResizableColumn;
    
    protected static string $resource = StartupResource::class;

    protected ?Collection $cachedCounts = null;

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
                ->badge(fn () => $isAdmin ? Startup::count() : Startup::where('founder', $user->name)->count()),

            'pending' => Tab::make('Pending')
                ->badge(fn () => $isAdmin 
                    ? Startup::where('status', 'pending')->count() 
                    : Startup::where('status', 'pending')->where('founder', $user->name)->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'pending')),

            'approved' => Tab::make('Approved')
                ->badge(fn () => $isAdmin 
                    ? Startup::where('status', 'approved')->count()
                    : Startup::where('status', 'approved')->where('founder', $user->name)->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'approved')),

            'rejected' => Tab::make('Rejected')
                ->badge(fn () => $isAdmin 
                    ? Startup::where('status', 'rejected')->count()
                    : Startup::where('status', 'rejected')->where('founder', $user->name)->count())
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
