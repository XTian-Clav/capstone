<?php

namespace App\Filament\Portal\Resources\ReserveRooms\Tables;

use Carbon\Carbon;
use Filament\Tables\Table;
use App\Models\ReserveRoom;
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
use Filament\Notifications\Notification;
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
use App\Filament\Actions\Room\RejectRoomAction;
use App\Filament\Actions\Room\ApproveRoomAction;
use App\Filament\Actions\Room\CompleteRoomAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Portal\Resources\ReserveRooms\Pages\ViewReserveRoom;

class ReserveRoomsTable
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

            ->emptyStateIcon('heroicon-o-building-office')
            ->emptyStateHeading('No room reservations found')
            ->emptyStateDescription('All reservations will appear here once its created.')

            ->contentGrid(['xl' => 2])
            ->columns([
                Stack::make([
                    TextColumn::make('room.room_type')
                        ->label('Name - Room')
                        ->searchable()
                        ->sortable()
                        ->weight('semibold'),
                        
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
                        ReserveRoom::query()
                            ->distinct()
                            ->select('reserved_by')
                            ->pluck('reserved_by', 'reserved_by') 
                            ->toArray()
                    )
                    ->searchable() 
                    ->placeholder('All Reservers'),
                
                SelectFilter::make('room')
                    ->label('Room')
                    ->relationship('room', 'room_type', fn (Builder $query) => $query->where('is_available', true))
                    ->searchable()
                    ->preload()
                    ->placeholder('All Room')
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

                ApproveRoomAction::make()->outlined()->size(Size::ExtraSmall),
                RejectRoomAction::make()->outlined()->size(Size::ExtraSmall),
                CompleteRoomAction::make()->outlined()->size(Size::ExtraSmall),
            ])
            ->headerActions([
                FilamentExportHeaderAction::make('export')
                    ->outlined()
                    ->color('secondary')
                    ->fileName('Room Reservations Report - ' . Carbon::now()->format('F Y'))
                    ->defaultFormat('pdf')
                    ->defaultPageOrientation('portrait')
                    ->disableTableColumns()
                    ->withColumns([
                        TextColumn::make('room.room_type'),
                        TextColumn::make('status'),
                        TextColumn::make('reserved_by'),
                        TextColumn::make('start_date')->dateTime('M j, Y h:i A'),
                        TextColumn::make('end_date')->dateTime('M j, Y h:i A'),
                        TextColumn::make('created_at')->dateTime('M j, Y h:i A')->label('Submitted At'),
                    ]),
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