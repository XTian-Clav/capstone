<?php

namespace App\Filament\Portal\Resources\Videos\Pages;


use App\Models\Video;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use App\Filament\Portal\Resources\Videos\VideoResource;

class ListVideos extends ListRecords
{
    protected static string $resource = VideoResource::class;

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
                ->badge(fn () => Video::count()),

            'archived' => Tab::make('Archive')
                ->badge(fn () => Video::onlyTrashed()->count())
                ->modifyQueryUsing(fn ($query) => $query->onlyTrashed()),
        ];
    }
}
