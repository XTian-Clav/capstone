<?php

namespace App\Filament\Portal\Resources\Rooms\Tables;

use Carbon\Carbon;
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
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;

class RoomsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('List of all Rooms')
            ->recordUrl(null)
            ->deferFilters(false)
            ->persistFiltersInSession()
            ->defaultSort('room_type', 'asc')

            ->emptyStateIcon('heroicon-o-building-office')
            ->emptyStateHeading('No rooms found')
            ->emptyStateDescription('All rooms will appear here once its created.')

            ->columns([
                TextColumn::make('room_type')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->width('35%')
                    ->weight('semibold')
                    ->verticallyAlignStart()
                    ->tooltip(fn ($record) => $record->room_type),

                TextColumn::make('capacity')
                    ->searchable()
                    ->sortable()
                    ->width('10%')
                    ->verticallyAlignStart(),

                TextColumn::make('location')
                    ->searchable()
                    ->sortable()
                    ->width('25%')
                    ->verticallyAlignStart(),

                TextColumn::make('room_rate')
                    ->sortable()
                    ->formatStateUsing(fn ($state) =>
                        ($state && $state > 0)
                            ? '₱' . number_format($state)
                            : 'None'
                    )
                    ->width('10%')
                    ->verticallyAlignStart(),

                TextColumn::make('is_available')
                    ->label('Availability')
                    ->formatStateUsing(fn ($state) => $state ? 'Available' : 'Unavailable')
                    ->color(fn ($state) => $state ? 'success' : 'danger')
                    ->width('10%')
                    ->sortable()
                    ->badge(),
                
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->width('10%')
                    ->since()
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->color('secondary')
                    ->verticallyAlignStart()
                    ->dateTimeTooltip('F j, Y g:i A'),
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
                ->icon('heroicon-o-bars-3')
                ->color('gray')
                ->size(Size::ExtraSmall)
                ->visible(fn () => auth()->user()->hasAnyRole(['super_admin', 'admin'])),

                ViewAction::make('alt_view')
                    ->button()
                    ->color('gray')
                    ->visible(fn () => auth()->user()->hasAnyRole(['incubatee', 'investor'])),
            ])
            ->headerActions([
                FilamentExportHeaderAction::make('export')
                    ->outlined()
                    ->size(Size::Small)
                    ->color('secondary')
                    ->fileName('Rooms Report')
                    ->defaultFormat('pdf')
                    ->defaultPageOrientation('portrait')
                    ->visible(fn () => auth()->user()->hasAnyRole(['super_admin', 'admin'])),
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
