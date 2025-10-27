<?php

namespace App\Filament\Portal\Resources\Startups;

use App\Filament\Portal\Resources\Startups\Pages\CreateStartup;
use App\Filament\Portal\Resources\Startups\Pages\EditStartup;
use App\Filament\Portal\Resources\Startups\Pages\ListStartups;
use App\Filament\Portal\Resources\Startups\Pages\ViewStartup;
use App\Filament\Portal\Resources\Startups\Schemas\StartupForm;
use App\Filament\Portal\Resources\Startups\Schemas\StartupInfolist;
use App\Filament\Portal\Resources\Startups\Tables\StartupsTable;
use App\Models\Startup;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class StartupResource extends Resource
{
    protected static ?string $model = Startup::class;

    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRocketLaunch;

    public static function getNavigationBadge(): ?string
    {
        return static::getEloquentQuery()->count();
    }

    protected static ?string $recordTitleAttribute = 'startup_name';

    public static function form(Schema $schema): Schema
    {
        return StartupForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StartupInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StartupsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStartups::route('/'),
            'create' => CreateStartup::route('/create'),
            //'view' => ViewStartup::route('/{record}'),
            'edit' => EditStartup::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if (! $user->hasAnyRole(['admin', 'super_admin', 'investor'])) {
            $query->where('user_id', $user->id);
        }

        return $query;
    }
}
