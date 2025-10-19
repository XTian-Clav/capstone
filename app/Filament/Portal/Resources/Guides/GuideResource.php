<?php

namespace App\Filament\Portal\Resources\Guides;

use App\Filament\Portal\Resources\Guides\Pages\CreateGuide;
use App\Filament\Portal\Resources\Guides\Pages\EditGuide;
use App\Filament\Portal\Resources\Guides\Pages\ListGuides;
use App\Filament\Portal\Resources\Guides\Pages\ViewGuide;
use App\Filament\Portal\Resources\Guides\Schemas\GuideForm;
use App\Filament\Portal\Resources\Guides\Schemas\GuideInfolist;
use App\Filament\Portal\Resources\Guides\Tables\GuidesTable;
use App\Models\Guide;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use UnitEnum;

class GuideResource extends Resource
{
    protected static ?string $model = Guide::class;

    protected static ?int $navigationSort = 2;

    protected static string | UnitEnum | null $navigationGroup = 'Learning Materials';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return GuideForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return GuideInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GuidesTable::configure($table);
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
            'index' => ListGuides::route('/'),
            'create' => CreateGuide::route('/create'),
            //'view' => ViewGuide::route('/{record}'),
            'edit' => EditGuide::route('/{record}/edit'),
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
