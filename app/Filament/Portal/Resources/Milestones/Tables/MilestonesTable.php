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
            ->defaultSort('created_at', 'asc')
            ->columns([
                TextColumn::make('startup.startup_name')
                    ->label('Startup')
                    ->sortable()
                    ->searchable()
                    ->weight('semibold'),
                TextColumn::make('title')
                    ->label('Task')
                    ->searchable(),
                TextColumn::make('description')
                    ->html(),
                ToggleColumn::make('is_done')
                    ->label('Task Status')
                    ->onColor('success')
                    ->offColor('danger')
                    ->disabled(fn () => ! auth()->user()->hasAnyRole(['admin', 'super_admin'])),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->since()
                    ->tooltip(fn ($record) => $record->created_at->format('F j, Y g:i A'))
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
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
