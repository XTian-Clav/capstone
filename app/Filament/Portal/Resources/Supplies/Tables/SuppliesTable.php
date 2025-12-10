<?php

namespace App\Filament\Portal\Resources\Supplies\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Size;
use Filament\Actions\ActionGroup;
use Filament\Actions\RestoreAction;
use Filament\Actions\BulkActionGroup;
use App\Filament\Actions\ArchiveAction;
use App\Filament\Filters\EndDateFilter;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Filters\StartDateFilter;
use App\Filament\Actions\ArchiveBulkAction;
use App\Filament\Filters\CreatedDateFilter;
use Filament\Actions\ForceDeleteBulkAction;
use App\Filament\Actions\Supply\UnavailableSupply;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;

class SuppliesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Available Supplies')
            ->deferLoading()
            ->recordUrl(null)
            ->deferFilters(false)
            ->persistFiltersInSession()
            ->defaultSort('item_name', 'asc')

            ->emptyStateIcon('heroicon-o-archive-box')
            ->emptyStateHeading('No supplies found')
            ->emptyStateDescription('All supplies will appear here once its created.')

            ->columns([
                TextColumn::make('item_name')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->width('40%')
                    ->weight('semibold')
                    ->verticallyAlignStart(),

                TextColumn::make('quantity')
                    ->numeric()
                    ->searchable()
                    ->sortable()
                    ->width('20%')
                    ->state(fn ($record) => 
                        $record->quantity === 0 ? 'Out of Stock':
                        $record->quantity . ' ' . ($record->quantity === 1 ? 'pc' : 'pcs')
                    )
                    ->verticallyAlignStart(),

                TextColumn::make('location')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->width('30%')
                    ->verticallyAlignStart(),

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->since()
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->width('10%')
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
                        ->icon('heroicon-s-archive-box-arrow-down')
                        ->label('Archive')
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
                UnavailableSupply::make()->visible(fn () => auth()->user()->hasAnyRole(['super_admin', 'admin'])),
                FilamentExportHeaderAction::make('export')
                    ->outlined()
                    ->size(Size::Small)
                    ->color('secondary')
                    ->fileName('Supplies Report')
                    ->defaultFormat('pdf')
                    ->defaultPageOrientation('landscape')
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
