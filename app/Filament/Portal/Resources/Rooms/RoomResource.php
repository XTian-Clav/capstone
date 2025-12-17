<?php

namespace App\Filament\Portal\Resources\Rooms;

use UnitEnum;
use BackedEnum;
use App\Models\Room;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Portal\Resources\Rooms\Pages\EditRoom;
use App\Filament\Portal\Resources\Rooms\Pages\ViewRoom;
use App\Filament\Portal\Resources\Rooms\Pages\ListRooms;
use App\Filament\Portal\Resources\Rooms\Pages\CreateRoom;
use App\Filament\Portal\Resources\Rooms\Schemas\RoomForm;
use App\Filament\Portal\Resources\Rooms\Tables\RoomsTable;
use App\Filament\Portal\Resources\Rooms\Schemas\RoomInfolist;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;

    protected static ?int $navigationSort = 1;

    protected static string | UnitEnum | null $navigationGroup = 'Inventory';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'super_admin']);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static ?string $recordTitleAttribute = 'room_type';

    public static function form(Schema $schema): Schema
    {
        return RoomForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RoomInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RoomsTable::configure($table);
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
            'index' => ListRooms::route('/'),
            'create' => CreateRoom::route('/create'),
            'view' => ViewRoom::route('/{record}'),
            'edit' => EditRoom::route('/{record}/edit'),
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
