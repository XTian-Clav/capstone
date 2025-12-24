<?php

namespace App\Filament\Portal\Resources\Users\Pages;

use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Asmit\ResizedColumn\HasResizableColumn;
use App\Filament\Portal\Resources\Users\UserResource;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Create User')->icon('heroicon-o-plus'),
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
                ->badge(fn () => User::count()),

            'archived' => Tab::make('Archive')
                ->badge(fn () => User::onlyTrashed()->count())
                ->modifyQueryUsing(fn ($query) => $query->onlyTrashed()),
        ];
    }
}
