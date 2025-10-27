<?php

namespace App\Filament\Portal\Resources\ReserveSupplies;

use App\Filament\Portal\Resources\ReserveSupplies\Pages\CreateReserveSupply;
use App\Filament\Portal\Resources\ReserveSupplies\Pages\EditReserveSupply;
use App\Filament\Portal\Resources\ReserveSupplies\Pages\ListReserveSupplies;
use App\Filament\Portal\Resources\ReserveSupplies\Pages\ViewReserveSupply;
use App\Filament\Portal\Resources\ReserveSupplies\Schemas\ReserveSupplyForm;
use App\Filament\Portal\Resources\ReserveSupplies\Schemas\ReserveSupplyInfolist;
use App\Filament\Portal\Resources\ReserveSupplies\Tables\ReserveSuppliesTable;
use App\Models\ReserveSupply;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use UnitEnum;

class ReserveSupplyResource extends Resource
{
    protected static ?string $model = ReserveSupply::class;

    protected static ?int $navigationSort = 3;

    protected static string | UnitEnum | null $navigationGroup = 'Reservation';

    public static function getNavigationBadge(): ?string
    {
        return static::getEloquentQuery()->count();
    }

    protected static ?string $recordTitleAttribute = 'reserved_by';

    public static function form(Schema $schema): Schema
    {
        return ReserveSupplyForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ReserveSupplyInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReserveSuppliesTable::configure($table);
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
            'index' => ListReserveSupplies::route('/'),
            'create' => CreateReserveSupply::route('/create'),
            //'view' => ViewReserveSupply::route('/{record}'),
            'edit' => EditReserveSupply::route('/{record}/edit'),
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
