<?php

namespace App\Filament\Portal\Resources\Events\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Enums\FiltersLayout;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->defaultSort('status', 'asc')
            ->columns([
                TextColumn::make('event')
                    ->searchable()
                    ->toggleable()
                    ->sortable()
                    ->limit(40)
                    ->weight('semibold')
                    ->tooltip(fn ($record) => $record->event),

                TextColumn::make('location')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),

                TextColumn::make('start_date')
                    ->dateTime('M j, Y h:i A')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('end_date')
                    ->dateTime('M j, Y h:i A')
                    ->sortable()
                    ->toggleable(),

                BadgeColumn::make('status')
                    ->searchable()
                    ->toggleable()
                    ->colors([
                        'warning' => 'Upcoming',
                        'info' => 'Ongoing',
                        'success' => 'Completed',
                        'danger' => 'Cancelled',
                    ]),
                    
                TextColumn::make('created_at')
                    ->dateTime('M j, Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime('M j, Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('deleted_at')
                    ->dateTime('M j, Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), 
            ])
            ->filters([
                SelectFilter::make('status')->native(false)
                ->options([
                    'Upcoming' => 'Upcoming',
                    'Ongoing' => 'Ongoing',
                    'Completed' => 'Completed',
                    'Cancelled' => 'Cancelled',
                ]),

                TrashedFilter::make('Archive')->native(false),
            ], layout: FiltersLayout::Modal)
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make()->color('success'),
                ForceDeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
