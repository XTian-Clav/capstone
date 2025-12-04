<?php

namespace App\Filament\Portal\Resources\UnavailableEquipment;

use App\Filament\Portal\Resources\UnavailableEquipment\Pages\CreateUnavailableEquipment;
use App\Filament\Portal\Resources\UnavailableEquipment\Pages\EditUnavailableEquipment;
use App\Filament\Portal\Resources\UnavailableEquipment\Pages\ListUnavailableEquipment;
use App\Filament\Portal\Resources\UnavailableEquipment\Pages\ViewUnavailableEquipment;
use App\Filament\Portal\Resources\UnavailableEquipment\Schemas\UnavailableEquipmentForm;
use App\Filament\Portal\Resources\UnavailableEquipment\Schemas\UnavailableEquipmentInfolist;
use App\Filament\Portal\Resources\UnavailableEquipment\Tables\UnavailableEquipmentTable;
use App\Models\UnavailableEquipment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UnavailableEquipmentResource extends Resource
{
    protected static ?string $model = UnavailableEquipment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'equipment_id';

    public static function form(Schema $schema): Schema
    {
        return UnavailableEquipmentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return UnavailableEquipmentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UnavailableEquipmentTable::configure($table);
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
            'index' => ListUnavailableEquipment::route('/'),
            'create' => CreateUnavailableEquipment::route('/create'),
            'view' => ViewUnavailableEquipment::route('/{record}'),
            'edit' => EditUnavailableEquipment::route('/{record}/edit'),
        ];
    }
}
