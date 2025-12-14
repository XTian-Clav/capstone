<?php

namespace App\Filament\Portal\Resources\Mentors;

use BackedEnum;
use App\Models\Mentor;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Portal\Resources\Mentors\Pages\EditMentor;
use App\Filament\Portal\Resources\Mentors\Pages\ViewMentor;
use App\Filament\Portal\Resources\Mentors\Pages\ListMentors;
use App\Filament\Portal\Resources\Mentors\Pages\CreateMentor;
use App\Filament\Portal\Resources\Mentors\Schemas\MentorForm;
use App\Filament\Portal\Resources\Mentors\Tables\MentorsTable;
use App\Filament\Portal\Resources\Mentors\Widgets\MentorWidget;
use App\Filament\Portal\Resources\Mentors\Schemas\MentorInfolist;

class MentorResource extends Resource
{
    protected static ?string $model = Mentor::class;

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedIdentification;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return MentorForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MentorInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MentorsTable::configure($table);
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
            'index' => ListMentors::route('/'),
            'create' => CreateMentor::route('/create'),
            'view' => ViewMentor::route('/{record}'),
            'edit' => EditMentor::route('/{record}/edit'),
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
