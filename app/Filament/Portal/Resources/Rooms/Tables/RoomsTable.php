<?php

namespace App\Filament\Portal\Resources\Rooms\Tables;

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
use App\Filament\Filters\EndDateFilter;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Enums\FiltersLayout;
use App\Filament\Filters\StartDateFilter;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use App\Filament\Actions\ArchiveBulkAction;
use App\Filament\Filters\CreatedDateFilter;
use Filament\Actions\ForceDeleteBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class RoomsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->deferFilters(false)
            ->persistFiltersInSession()
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('room_type')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->width('30%')
                    ->weight('semibold')
                    ->tooltip(fn ($record) => $record->room_type),

                TextColumn::make('capacity')
                    ->searchable()
                    ->sortable()
                    ->width('10%'),

                TextColumn::make('location')
                    ->searchable()
                    ->toggleable()
                    ->sortable()
                    ->width('30%'),

                TextColumn::make('room_rate')
                    ->sortable()
                    ->toggleable()
                    ->formatStateUsing(fn ($state) =>
                        ($state && $state > 0)
                            ? '₱' . number_format($state)
                            : 'None'
                    )
                    ->width('10%'),

                ToggleColumn::make('is_available')
                    ->width('10%')
                    ->label('Available')
                    ->onColor('success')
                    ->offColor('danger')
                    ->disabled(fn () => ! auth()->user()->hasAnyRole(['admin', 'super_admin']))
                    ->visible(fn () => auth()->user()->hasAnyRole(['admin', 'super_admin'])),
                
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->width('10%')
                    ->since()
                    ->tooltip(fn ($record) => $record->created_at->format('F j, Y g:i A'))
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                CreatedDateFilter::make('created_at')->columnSpan(2),
                StartDateFilter::make(),
                EndDateFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()->color('gray'),
                    EditAction::make()->color('gray')
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
                    ExportBulkAction::make()
                        //Exclude is_available column
                        ->exports([
                            ExcelExport::make()->fromTable()->except(['is_available']),
                            ExcelExport::make()->fromTable()->except(['availability_text']),
                        ])
                        ->color('gray')
                        ->authorize(fn () => auth()->user()->hasAnyRole(['admin', 'super_admin'])),
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
