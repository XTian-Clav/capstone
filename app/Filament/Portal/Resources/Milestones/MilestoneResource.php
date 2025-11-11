<?php

namespace App\Filament\Portal\Resources\Milestones;

use UnitEnum;
use BackedEnum;
use App\Models\Milestone;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Portal\Resources\Milestones\Pages\EditMilestone;
use App\Filament\Portal\Resources\Milestones\Pages\ViewMilestone;
use App\Filament\Portal\Resources\Milestones\Pages\ListMilestones;
use App\Filament\Portal\Resources\Milestones\Pages\CreateMilestone;
use App\Filament\Portal\Resources\Milestones\Schemas\MilestoneForm;
use App\Filament\Portal\Resources\Milestones\Tables\MilestonesTable;
use App\Filament\Portal\Resources\Milestones\Schemas\MilestoneInfolist;

class MilestoneResource extends Resource
{
    protected static ?string $model = Milestone::class;

    protected static ?int $navigationSort = 2;

    protected static string | UnitEnum | null $navigationGroup = 'Startup';

    public static function getNavigationBadge(): ?string
    {
        return static::getEloquentQuery()->count();
    }

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return MilestoneForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MilestoneInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MilestonesTable::configure($table);
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
            'index' => ListMilestones::route('/'),
            'create' => CreateMilestone::route('/create'),
            'view' => ViewMilestone::route('/{record}'),
            'edit' => EditMilestone::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();
    
        // Only restrict non-admins to their own startups
        if (! $user->hasAnyRole(['admin', 'super_admin', 'investor'])) {
            $query->whereHas('startup', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }
    
        return $query;
    }
}
