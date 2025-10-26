<?php

namespace App\Filament\Portal\Resources\NonUsers;

use App\Filament\Portal\Resources\NonUsers\Pages\CreateNonUser;
use App\Filament\Portal\Resources\NonUsers\Pages\EditNonUser;
use App\Filament\Portal\Resources\NonUsers\Pages\ListNonUsers;
use App\Filament\Portal\Resources\NonUsers\Pages\ViewNonUser;
use App\Filament\Portal\Resources\NonUsers\Schemas\NonUserForm;
use App\Filament\Portal\Resources\NonUsers\Schemas\NonUserInfolist;
use App\Filament\Portal\Resources\NonUsers\Tables\NonUsersTable;
use App\Models\NonUser;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use UnitEnum;

class NonUserResource extends Resource
{
    protected static ?string $model = NonUser::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'External Users';

    protected static string | UnitEnum | null $navigationGroup = 'Manage Users';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return NonUserForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return NonUserInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NonUsersTable::configure($table);
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
            'index' => ListNonUsers::route('/'),
            'create' => CreateNonUser::route('/create'),
            //'view' => ViewNonUser::route('/{record}'),
            'edit' => EditNonUser::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
