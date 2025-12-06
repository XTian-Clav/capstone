<?php

namespace App\Filament\Portal\Resources\UnavailableSupplies\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Size;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Filters\EndDateFilter;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Filters\StartDateFilter;
use App\Filament\Filters\CreatedDateFilter;
use App\Filament\Actions\Supply\AvailableSupply;

class UnavailableSuppliesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Unavailable Supplies')
            ->deferLoading()
            ->recordUrl(null)
            ->deferFilters(false)
            ->persistFiltersInSession()
            ->defaultSort('created_at', 'desc')

            ->emptyStateIcon('heroicon-o-archive-box')
            ->emptyStateHeading('No unavailable supplies found')
            ->emptyStateDescription('All unavailable supplies will appear here once its created.')

            ->columns([
                TextColumn::make('supply.item_name')
                    ->wrap()
                    ->label('Supply Name')
                    ->sortable()
                    ->width('30%')
                    ->searchable()
                    ->weight('semibold')
                    ->verticallyAlignStart(),
            
                TextColumn::make('unavailable_quantity')
                    ->numeric()
                    ->sortable()
                    ->width('10%')
                    ->searchable()
                    ->label('Quantity')
                    ->verticallyAlignStart()
                    ->formatStateUsing(fn (int $state): string => 
                        $state . ' ' . ($state === 1 ? 'pc' : 'pcs')
                    ),

                TextColumn::make('remarks')
                    ->wrap()
                    ->width('40%')
                    ->searchable()
                    ->verticallyAlignStart(),

                TextColumn::make('status')
                    ->badge()
                    ->width('10%')
                    ->searchable()
                    ->color('danger'),
                
                TextColumn::make('created_at')
                    ->badge()
                    ->since()
                    ->sortable()
                    ->width('10%')
                    ->searchable()
                    ->color('secondary')
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
                        ->authorize(fn () => auth()->user()->hasAnyRole(['admin', 'super_admin'])),
                    DeleteAction::make()
                        ->color('danger')
                        ->authorize(fn () => auth()->user()->hasRole('super_admin')),
                ])
                ->icon('heroicon-o-bars-3')
                ->color('gray')
                ->size(Size::ExtraSmall)
                ->visible(fn () => auth()->user()->hasAnyRole(['super_admin', 'admin'])),
            ])
            ->headerActions([
                AvailableSupply::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
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