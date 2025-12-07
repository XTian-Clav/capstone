<?php

namespace App\Filament\Portal\Resources\ReserveSupplies\Tables;

use Carbon\Carbon;
use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Models\ReserveSupply;
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
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use App\Filament\Actions\ArchiveBulkAction;
use App\Filament\Filters\CreatedDateFilter;
use Filament\Actions\ForceDeleteBulkAction;
use App\Filament\Actions\Supply\RejectSupplyAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Actions\Supply\ApproveSupplyAction;
use App\Filament\Actions\Supply\CompleteSupplyAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;

class ReserveSuppliesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->poll('10s')
            ->deferLoading()
            ->recordUrl(null)
            ->deferFilters(false)
            ->persistFiltersInSession()
            ->defaultSort('created_at', 'desc')

            ->emptyStateIcon('heroicon-o-archive-box')
            ->emptyStateHeading('No supply reservations found')
            ->emptyStateDescription('All reservations will appear here once its created.')

            ->contentGrid(['xl' => 2])
            ->columns([
                Stack::make([
                    TextColumn::make('supply.item_name')
                        ->label('Name - Supply')
                        ->searchable()
                        ->sortable()
                        ->weight('semibold'),
                        
                    TextColumn::make('quantity')
                        ->badge()
                        ->sortable()
                        ->searchable()
                        ->color('secondary')
                        ->formatStateUsing(fn ($state) => 'quantity: ' . $state . ' ' . ($state == 1 ? 'pc' : 'pcs')),
                        
                    TextColumn::make('status')
                        ->weight('semibold')
                        ->badge()
                        ->colors([
                            'warning' => 'Pending',
                            'success' => 'Approved',
                            'danger' => 'Rejected',
                            'cyan' => 'Completed',
                        ]),

                    TextColumn::make('start_date')
                        ->formatStateUsing(fn ($record) => 'Start: ' . ($record->start_date?->format('F j g:i A') ?? '—'))
                        ->extraAttributes(['style' => 'margin-top: 0.75rem;'])
                        ->searchable()
                        ->sortable(),

                    TextColumn::make('end_date')
                        ->formatStateUsing(fn ($record) => 'End: ' . ($record->end_date?->format('F j g:i A') ?? '—'))
                        ->searchable()
                        ->sortable(),
                        
                    TextColumn::make('reserved_by')
                        ->sortable()
                        ->searchable()
                        ->weight('semibold')
                        ->label('Name - Reserver')
                        ->extraAttributes(['style' => 'margin-top: 0.75rem;'])
                        ->formatStateUsing(fn ($state) => 'Reserved by: ' . ($state ?? '—')),
                    
                    TextColumn::make('created_at')
                        ->badge()
                        ->since()
                        ->sortable()
                        ->searchable()
                        ->color('gray')
                        ->label('Submission Date')
                        ->dateTimeTooltip('F j, Y g:i A'),
                ])->space(1)
            ])
            ->filters([
                CreatedDateFilter::make('created_at')->columnSpan(2),
                StartDateFilter::make(),
                EndDateFilter::make(),
                SelectFilter::make('reserved_by')
                    ->label('Reserver Name')
                    ->options(
                        ReserveSupply::query()
                            ->distinct()
                            ->select('reserved_by')
                            ->pluck('reserved_by', 'reserved_by') 
                            ->toArray()
                    )
                    ->searchable() 
                    ->placeholder('All Reservers'),
                
                SelectFilter::make('supply')
                    ->label('Supply')
                    ->relationship('supply', 'item_name', fn (Builder $query) => $query->where('quantity', '>', 0))
                    ->searchable()
                    ->preload()
                    ->placeholder('All Supply'),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()->color('gray'),
                    EditAction::make()->color('gray')
                        ->authorize(fn () => auth()->user()->hasAnyRole(['admin', 'super_admin'])),
                    DeleteAction::make()
                        ->color('danger')
                        ->icon('heroicon-s-archive-box-x-mark')
                        ->authorize(fn () => auth()->user()->hasRole('super_admin')),
                ])
                ->label('Actions')
                ->icon('heroicon-o-bars-arrow-down')
                ->color('gray')
                ->size(Size::ExtraSmall)
                ->button()
                ->visible(fn () => auth()->user()->hasAnyRole(['super_admin', 'admin'])),

                ViewAction::make('alt_view')
                    ->button()
                    ->color('gray')
                    ->visible(fn () => auth()->user()->hasAnyRole(['incubatee', 'investor'])),

                ApproveSupplyAction::make()->outlined()->size(Size::ExtraSmall),
                RejectSupplyAction::make()->outlined()->size(Size::ExtraSmall),
                CompleteSupplyAction::make()->outlined()->size(Size::ExtraSmall),
            ])
            ->headerActions([
                FilamentExportHeaderAction::make('export')
                    ->outlined()
                    ->color('secondary')
                    ->fileName('Supply Reservations Report - ' . Carbon::now()->format('F Y'))
                    ->defaultFormat('pdf')
                    ->defaultPageOrientation('portrait')
                    ->disableTableColumns()
                    ->withColumns([
                        TextColumn::make('supply.item_name'),
                        TextColumn::make('status'),
                        TextColumn::make('reserved_by'),
                        TextColumn::make('start_date')->dateTime('M j, Y h:i A'),
                        TextColumn::make('end_date')->dateTime('M j, Y h:i A'),
                        TextColumn::make('created_at')->dateTime('M j, Y h:i A')->label('Submitted At'),
                    ])
                    ->visible(fn () => auth()->user()->hasAnyRole(['super_admin', 'admin'])),
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