<?php

namespace App\Filament\Portal\Resources\UnavailableSupplies;

use App\Filament\Portal\Resources\UnavailableSupplies\Pages\CreateUnavailableSupply;
use App\Filament\Portal\Resources\UnavailableSupplies\Pages\EditUnavailableSupply;
use App\Filament\Portal\Resources\UnavailableSupplies\Pages\ListUnavailableSupplies;
use App\Filament\Portal\Resources\UnavailableSupplies\Pages\ViewUnavailableSupply;
use App\Filament\Portal\Resources\UnavailableSupplies\Schemas\UnavailableSupplyForm;
use App\Filament\Portal\Resources\UnavailableSupplies\Schemas\UnavailableSupplyInfolist;
use App\Filament\Portal\Resources\UnavailableSupplies\Tables\UnavailableSuppliesTable;
use App\Models\UnavailableSupply;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UnavailableSupplyResource extends Resource
{
    protected static ?string $model = UnavailableSupply::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'supply_id';

    public static function form(Schema $schema): Schema
    {
        return UnavailableSupplyForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return UnavailableSupplyInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UnavailableSuppliesTable::configure($table);
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
            'index' => ListUnavailableSupplies::route('/'),
            'create' => CreateUnavailableSupply::route('/create'),
            'view' => ViewUnavailableSupply::route('/{record}'),
            'edit' => EditUnavailableSupply::route('/{record}/edit'),
        ];
    }
}
