<?php

namespace App\Filament\Portal\Resources\Equipment;

use App\Filament\Portal\Resources\Equipment\Pages\CreateEquipment;
use App\Filament\Portal\Resources\Equipment\Pages\EditEquipment;
use App\Filament\Portal\Resources\Equipment\Pages\ListEquipment;
use App\Filament\Portal\Resources\Equipment\Pages\ViewEquipment;
use App\Filament\Portal\Resources\Equipment\Schemas\EquipmentForm;
use App\Filament\Portal\Resources\Equipment\Schemas\EquipmentInfolist;
use App\Filament\Portal\Resources\Equipment\Tables\EquipmentTable;
use App\Models\Equipment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use UnitEnum;

class EquipmentResource extends Resource
{
    protected static ?string $model = Equipment::class;

    protected static ?int $navigationSort = 2;

    protected static string | UnitEnum | null $navigationGroup = 'Inventory';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static ?string $recordTitleAttribute = 'equipment_name';

    public static function form(Schema $schema): Schema
    {
        return EquipmentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EquipmentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EquipmentTable::configure($table);
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
            'index' => ListEquipment::route('/'),
            'create' => CreateEquipment::route('/create'),
            'view' => ViewEquipment::route('/{record}'),
            'edit' => EditEquipment::route('/{record}/edit'),
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
