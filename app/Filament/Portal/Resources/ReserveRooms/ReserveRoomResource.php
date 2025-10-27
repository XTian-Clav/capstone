<?php

namespace App\Filament\Portal\Resources\ReserveRooms;

use App\Filament\Portal\Resources\ReserveRooms\Pages\CreateReserveRoom;
use App\Filament\Portal\Resources\ReserveRooms\Pages\EditReserveRoom;
use App\Filament\Portal\Resources\ReserveRooms\Pages\ListReserveRooms;
use App\Filament\Portal\Resources\ReserveRooms\Pages\ViewReserveRoom;
use App\Filament\Portal\Resources\ReserveRooms\Schemas\ReserveRoomForm;
use App\Filament\Portal\Resources\ReserveRooms\Schemas\ReserveRoomInfolist;
use App\Filament\Portal\Resources\ReserveRooms\Tables\ReserveRoomsTable;
use App\Models\ReserveRoom;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use UnitEnum;

class ReserveRoomResource extends Resource
{
    protected static ?string $model = ReserveRoom::class;

    protected static ?int $navigationSort = 1;

    protected static string | UnitEnum | null $navigationGroup = 'Reservation';

    public static function getNavigationBadge(): ?string
    {
        return static::getEloquentQuery()->count();
    }

    protected static ?string $recordTitleAttribute = 'reserved_by';

    public static function form(Schema $schema): Schema
    {
        return ReserveRoomForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ReserveRoomInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReserveRoomsTable::configure($table);
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
            'index' => ListReserveRooms::route('/'),
            'create' => CreateReserveRoom::route('/create'),
            //'view' => ViewReserveRoom::route('/{record}'),
            'edit' => EditReserveRoom::route('/{record}/edit'),
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

        if (! $user->hasAnyRole(['admin', 'super_admin'])) {
            $query->where('user_id', $user->id);
        }

        return $query;
    }
}
