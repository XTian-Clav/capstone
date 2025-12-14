<?php

namespace App\Filament\Portal\Resources\Mentors\Tables;

use App\Models\Startup;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Size;
use Filament\Actions\ActionGroup;
use Filament\Actions\RestoreAction;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\BulkActionGroup;
use App\Filament\Actions\ArchiveAction;
use App\Filament\Filters\EndDateFilter;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use App\Filament\Filters\StartDateFilter;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use App\Filament\Actions\ArchiveBulkAction;
use App\Filament\Filters\CreatedDateFilter;
use Filament\Actions\ForceDeleteBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;

class MentorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->deferFilters(false)
            ->persistFiltersInSession()
            ->defaultSort('created_at', 'desc')

            ->emptyStateIcon('heroicon-o-identification')
            ->emptyStateHeading('No mentors found')
            ->emptyStateDescription('All mentors will appear here once its created.')
            ->columns([
                Split::make ([
                    ImageColumn::make('avatar')
                        ->label('')
                        ->disk('public')
                        ->size(50)
                        ->circular()
                        ->grow(false)
                        ->defaultImageUrl(url('storage/default/user.png')),
                    
                    Stack::make([
                        TextColumn::make('name')
                            ->weight('semibold')
                            ->searchable()
                            ->sortable(),

                        TextColumn::make('expertise')
                            ->color('success')
                            ->searchable()
                            ->badge(),
                    ])->space(1),
                    
                    Stack::make([
                        TextColumn::make('email')
                            ->searchable()
                            ->sortable()
                            ->badge()
                            ->icon(Heroicon::Envelope),
                    
                        TextColumn::make('contact')
                            ->searchable()
                            ->badge()
                            ->icon(Heroicon::Phone),
                    ])->space(1),
                    
                    TextColumn::make('schedules')
                        ->label('Schedules')
                        ->getStateUsing(function ($record) {
                            $schedules = $record->schedules;
                            if (!$schedules) return [];

                            return collect($schedules)->map(function ($item) {
                                $startupNames = is_array($item['startup'] ?? null)
                                    ? implode(', ', $item['startup'])
                                    : (Startup::find($item['startup'])?->startup_name ?? 'N/A');

                                return "$startupNames | {$item['day']} {$item['start_time']} - {$item['end_time']}";
                            })->toArray();
                        })
                        ->listWithLineBreaks(),
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
                ->color('gray')
                ->size(Size::ExtraSmall)
                ->button()
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
                    ->color('success')
                    ->fileName('Mentors Report')
                    ->defaultFormat('pdf')
                    ->defaultPageOrientation('portrait')
                    ->disableTableColumns()
                    ->withColumns([
                        TextColumn::make('name'),
                        TextColumn::make('expertise'),
                        TextColumn::make('email'),
                        TextColumn::make('contact'),
                    ])
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
