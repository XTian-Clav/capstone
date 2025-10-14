<?php

namespace App\Filament\Resources\ReserveEquipment;

use App\Filament\Resources\ReserveEquipment\Pages\CreateReserveEquipment;
use App\Filament\Resources\ReserveEquipment\Pages\EditReserveEquipment;
use App\Filament\Resources\ReserveEquipment\Pages\ListReserveEquipment;
use App\Filament\Resources\ReserveEquipment\Pages\ViewReserveEquipment;
use App\Filament\Resources\ReserveEquipment\Schemas\ReserveEquipmentForm;
use App\Filament\Resources\ReserveEquipment\Schemas\ReserveEquipmentInfolist;
use App\Filament\Resources\ReserveEquipment\Tables\ReserveEquipmentTable;
use App\Models\ReserveEquipment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReserveEquipmentResource extends Resource
{
    protected static ?string $model = ReserveEquipment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

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
            //'view' => ViewReserveEquipment::route('/{record}'),
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
}
