<?php

namespace App\Filament\Portal\Resources\Equipment\Tables;

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
use App\Filament\Actions\Equipment\UnavailableEquipment;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;

class EquipmentTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Available Equipments')
            ->deferLoading()
            ->recordUrl(null)
            ->deferFilters(false)
            ->persistFiltersInSession()
            ->defaultSort('equipment_name', 'asc')

            ->emptyStateIcon('heroicon-o-wrench-screwdriver')
            ->emptyStateHeading('No equipments found')
            ->emptyStateDescription('All equipments will appear here once its created.')

            ->columns([
                TextColumn::make('equipment_name')
                    ->searchable()
                    ->width('35%')
                    ->sortable()
                    ->wrap()
                    ->weight('semibold')
                    ->verticallyAlignStart(),

                TextColumn::make('quantity')
                    ->numeric()
                    ->sortable()
                    ->width('10%')
                    ->searchable()
                    ->getStateUsing(fn ($record): string => 
                        $record->quantity === 0
                            ? 'Out of Stock'
                            : $record->quantity . ' ' . ($record->quantity === 1 ? 'pc' : 'pcs')
                    )
                    ->verticallyAlignStart(),
                    
                TextColumn::make('property_no')
                    ->searchable()
                    ->width('20%')
                    ->sortable()
                    ->wrap()
                    ->verticallyAlignStart(),

                TextColumn::make('location')
                    ->searchable()
                    ->width('25%')
                    ->sortable()
                    ->wrap()
                    ->verticallyAlignStart(),
                
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->since()
                    ->badge()
                    ->sortable()
                    ->width('10%')
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
                UnavailableEquipment::make()->visible(fn () => auth()->user()->hasAnyRole(['super_admin', 'admin'])),
                FilamentExportHeaderAction::make('export')
                    ->outlined()
                    ->size(Size::Small)
                    ->color('secondary')
                    ->fileName('Equipment Report')
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
