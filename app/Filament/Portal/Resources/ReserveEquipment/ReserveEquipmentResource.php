<?php

namespace App\Filament\Portal\Resources\ReserveEquipment;

use App\Filament\Portal\Resources\ReserveEquipment\Pages\CreateReserveEquipment;
use App\Filament\Portal\Resources\ReserveEquipment\Pages\EditReserveEquipment;
use App\Filament\Portal\Resources\ReserveEquipment\Pages\ListReserveEquipment;
use App\Filament\Portal\Resources\ReserveEquipment\Pages\ViewReserveEquipment;
use App\Filament\Portal\Resources\ReserveEquipment\Schemas\ReserveEquipmentForm;
use App\Filament\Portal\Resources\ReserveEquipment\Schemas\ReserveEquipmentInfolist;
use App\Filament\Portal\Resources\ReserveEquipment\Tables\ReserveEquipmentTable;
use App\Models\ReserveEquipment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use UnitEnum;

class ReserveEquipmentResource extends Resource
{
    protected static ?string $model = ReserveEquipment::class;

    protected static ?int $navigationSort = 2;

    protected static string | UnitEnum | null $navigationGroup = 'Reservation';

    public static function getNavigationBadge(): ?string
    {
        return static::getEloquentQuery()->count();
    }

    protected static ?string $recordTitleAttribute = 'reserved_by';

    public static function form(Schema $schema): Schema
    {
        return ReserveEquipmentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ReserveEquipmentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReserveEquipmentTable::configure($table);
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
            'index' => ListReserveEquipment::route('/'),
            'create' => CreateReserveEquipment::route('/create'),
            'view' => ViewReserveEquipment::route('/{record}'),
            'edit' => EditReserveEquipment::route('/{record}/edit'),
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
