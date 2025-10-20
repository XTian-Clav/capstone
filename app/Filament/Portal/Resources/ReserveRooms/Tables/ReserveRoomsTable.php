<?php

namespace App\Filament\Portal\Resources\ReserveRooms\Tables;

use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Size;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Tables\Filters\Filter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Actions\ArchiveAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use App\Filament\Actions\ArchiveBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReserveRoomsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->defaultSort('created_at', 'asc')
            ->columns([
                TextColumn::make('room.room_name')
                    ->label('Room')
                    ->searchable()
                    ->toggleable()
                    ->sortable()
                    ->weight('semibold'),

                TextColumn::make('reserved_by')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),

                TextColumn::make('room.capacity')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),

                TextColumn::make('start_date')
                    ->dateTime('m-d-y g:i A')
                    ->tooltip(fn ($record) => $record->created_at->format('F j, Y g:i A'))
                    ->searchable()
                    ->toggleable()
                    ->sortable(),

                TextColumn::make('end_date')
                    ->dateTime('m-d-y g:i A')
                    ->tooltip(fn ($record) => $record->created_at->format('F j, Y g:i A'))
                    ->searchable()
                    ->toggleable()
                    ->sortable(),

                BadgeColumn::make('status')
                    ->searchable()
                    ->toggleable()
                    ->colors([
                        'warning' => 'Pending',
                        'success' => 'Approved',
                        'danger' => 'Rejected',
                    ]),
                    
                TextColumn::make('created_at')
                    ->label('Submitted At')
                    ->dateTime('m-d-y g:i A')
                    ->tooltip(fn ($record) => $record->created_at->format('F j, Y g:i A'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->dateTime('F j, Y g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('deleted_at')
                    ->dateTime('F j, Y g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('today')
                    ->toggle()
                    ->label('Created Today')
                    ->query(fn ($query, $state) => 
                        $state
                            ? $query->whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])
                                    ->reorder('created_at', 'asc')
                            : null
                    ),
                //TrashedFilter::make()->native(false),
            ], layout: FiltersLayout::Modal)
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()->color('secondary'),
                    EditAction::make()->color('secondary')
                        ->visible(fn ($record) => ! $record->trashed())
                        ->authorize(fn () => auth()->user()->hasAnyRole(['admin', 'super_admin'])),
                    RestoreAction::make()
                        ->color('success')
                        ->authorize(fn () => auth()->user()->hasRole('super_admin')),
                    ArchiveAction::make()
                        ->authorize(fn () => auth()->user()->hasAnyRole(['admin', 'super_admin'])),
                    ForceDeleteAction::make()->color('danger')
                        ->icon('heroicon-s-archive-box-x-mark')
                        ->authorize(fn () => auth()->user()->hasRole('super_admin')),
                ])
                ->label('Actions')
                ->icon('heroicon-o-bars-arrow-down')
                ->color('secondary')
                ->size(Size::ExtraSmall)
                ->button(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    RestoreBulkAction::make()
                        ->color('success')
                        ->authorize(fn () => auth()->user()->hasRole('super_admin')),
                    ArchiveBulkAction::make(),
                    ForceDeleteBulkAction::make()
                        ->icon('heroicon-s-archive-box-x-mark')
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