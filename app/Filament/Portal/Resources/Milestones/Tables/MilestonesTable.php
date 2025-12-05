<?php

namespace App\Filament\Portal\Resources\Milestones\Tables;

use App\Models\User;
use App\Models\Startup;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Size;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Filters\EndDateFilter;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Filters\StartDateFilter;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Filters\CreatedDateFilter;

class MilestonesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->deferFilters(false)
            ->persistFiltersInSession()
            ->defaultGroup('startup.startup_name')
            ->defaultSort('created_at', 'desc')

            ->emptyStateIcon('heroicon-o-trophy')
            ->emptyStateHeading('No milestones posted yet')
            ->emptyStateDescription('All milestones will appear here once its created.')

            ->contentGrid(['xl' => 3])
            ->columns([
                Stack::make([
                    TextColumn::make('startup.startup_name')
                        ->label('Startup')
                        ->sortable()
                        ->searchable()
                        ->weight('semibold'),
                    
                    TextColumn::make('title')
                        ->label('Task')
                        ->sortable()
                        ->searchable()
                        ->color('secondary')
                        ->weight('semibold'),
                        
                    TextColumn::make('is_done')
                        ->badge()
                        ->sortable()
                        ->label('Task Status')
                        ->formatStateUsing(fn (bool $state) => $state ? 'Complete' : 'Incomplete')
                        ->color(fn (bool $state) => $state ? 'success' : 'danger'),
                    TextColumn::make('description')
                        ->html()
                        ->extraAttributes(['style' => 'margin-top: 0.75rem;']),
                    TextColumn::make('created_at')
                        ->label('Created At')
                        ->since()
                        ->badge()
                        ->sortable()
                        ->searchable()
                        ->color('gray')
                        ->dateTimeTooltip('F j, Y g:i A')
                        ->extraAttributes(['style' => 'margin-top: 0.75rem;'])
                        ->formatStateUsing(fn ($state, $record) => 'Created At: ' . $record->created_at->diffForHumans()),
                ])->space(1)
            ])
            ->filters([
                CreatedDateFilter::make('created_at')->columnSpan(2),
                StartDateFilter::make(),
                EndDateFilter::make(),
                SelectFilter::make('startup_id')
                    ->label('Founder')
                    ->searchable()
                    ->native(false)
                    ->options(User::pluck('name', 'id'))
                    ->query(function ($query, $data) {
                        if (blank($data['value'])) {
                            return $query;
                        }
                        return $query->whereHas('startup', fn ($q) =>
                            $q->where('user_id', $data['value'])
                        );
                    })
                    ->visible(fn () => auth()->user()->hasAnyRole(['admin', 'super_admin'])),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()->color('gray'),
                    EditAction::make()->color('gray')
                        ->label(fn () => auth()->user()->hasAnyRole(['admin', 'super_admin']) 
                        ? 'Edit Task' 
                        : 'Comply Task'),
                    DeleteAction::make()
                        ->authorize(fn () => auth()->user()->hasAnyRole(['admin', 'super_admin'])),
                ])
                ->label('Actions')
                ->icon('heroicon-o-bars-arrow-down')
                ->color('secondary')
                ->size(Size::ExtraSmall)
                ->button(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->authorize(fn () => auth()->user()->hasRole('super_admin')),
                ])
                ->label('Bulk Actions')
                ->icon('heroicon-s-cog-6-tooth')
                ->color('info')
                ->size(Size::Small)
                ->button(),
            ]);
    }
}
