<?php

namespace App\Filament\Portal\Resources\Events\Tables;

use Carbon\Carbon;
use App\Models\Event;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Size;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Tables\Filters\Filter;
use Filament\Support\Enums\TextSize;
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
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use App\Filament\Actions\ArchiveBulkAction;
use App\Filament\Filters\CreatedDateFilter;
use Filament\Actions\ForceDeleteBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->deferFilters(false)
            ->persistFiltersInSession()
            ->defaultSort('created_at', 'desc')
            ->contentGrid(['xl' => 2])
            ->columns([
                Split::make([
                    ImageColumn::make('picture')
                        ->label('')
                        ->imageHeight(150)
                        ->imageWidth(250)
                        ->grow(false)
                        ->disk('public')
                        ->defaultImageUrl(url('storage/default/no-image.png'))
                        ->extraImgAttributes([
                            'alt' => 'Logo',
                            'loading' => 'lazy',
                            'class' => 'rounded-xl object-cover',
                        ]),
                    Stack::make([
                        Split::make([
                            TextColumn::make('event')
                                ->searchable()
                                ->sortable()
                                ->limit(40)
                                ->color('primary')
                                ->weight('semibold')
                                ->tooltip(fn ($record) => $record->event),
    
                            TextColumn::make('status')
                                ->getStateUsing(fn ($record) => $record->status)
                                ->badge()
                                ->colors([
                                    'indigo' => 'Upcoming',
                                    'warning' => 'Ongoing',
                                    'success' => 'Completed',
                                    'danger' => 'Cancelled',
                                ]),
                        ]),
                        
                        TextColumn::make('location')
                            ->searchable()
                            ->sortable()
                            ->weight('semibold'),
                        
                        TextColumn::make('start_date')
                            ->dateTime('M j, Y h:i A')
                            ->badge()
                            ->sortable()
                            ->color('gray'),

                        TextColumn::make('end_date')
                            ->dateTime('M j, Y h:i A')
                            ->sortable()
                            ->badge()
                            ->color('gray'),

                        TextColumn::make('attendance')
                            ->badge()
                            ->extraAttributes(['style' => 'margin-top: 0.75rem;'])
                            ->getStateUsing(fn($record) => 'Going: ' . collect($record->attendance ?? [])
                                ->where('status', 'yes')
                                ->count()
                            ),
                    ])->space(2)
                ])->from('md')
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
                ->button()
                ->visible(fn () => auth()->user()->hasAnyRole(['super_admin', 'admin'])),

                ViewAction::make('alt_view')
                    ->button()
                    ->color('gray')
                    ->visible(fn () => auth()->user()->hasAnyRole(['incubatee', 'investor'])),
                
                EditAction::make('attendance')
                    ->button()
                    ->color('secondary')
                    ->label('Attendance')
                    ->icon('heroicon-s-clipboard-document-check')
                    ->visible(fn () => auth()->user()->hasAnyRole(['incubatee', 'investor'])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    ExportBulkAction::make()
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
