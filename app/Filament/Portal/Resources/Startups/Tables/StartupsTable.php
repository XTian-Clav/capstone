<?php

namespace App\Filament\Portal\Resources\Startups\Tables;

use App\Models\Startup;
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
use Filament\Tables\Columns\ImageColumn;
use App\Filament\Filters\StartDateFilter;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Actions\ArchiveBulkAction;
use App\Filament\Filters\CreatedDateFilter;
use Filament\Actions\ForceDeleteBulkAction;
use App\Filament\Actions\Startup\RejectStartupAction;
use App\Filament\Actions\Startup\ApproveStartupAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;

class StartupsTable
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

            ->emptyStateIcon('heroicon-o-rocket-launch')
            ->emptyStateHeading('No startups found')
            ->emptyStateDescription('All startups will appear here once its created.')

            ->contentGrid(['xl' => 2])
            ->columns([
                Split::make([
                    ImageColumn::make('logo')
                        ->label('')
                        ->square()
                        ->imageSize(180)
                        ->grow(false)
                        ->disk('public')
                        ->defaultImageUrl(url('storage/default/no-image.png'))
                        ->extraImgAttributes([
                            'alt' => 'Logo',
                            'loading' => 'lazy',
                            'class' => 'rounded-xl object-cover',
                        ])
                        ->alignCenter(),

                    Stack::make([
                    TextColumn::make('startup_name')
                        ->searchable()
                        ->sortable()
                        ->weight('semibold'),
    
                    TextColumn::make('founder')
                        ->searchable()
                        ->sortable(),
                    
                    TextColumn::make('status')
                        ->weight('semibold')
                        ->sortable()
                        ->badge()
                        ->colors([
                            'warning' => 'Pending',
                            'success' => 'Approved',
                            'danger' => 'Rejected',
                        ])
                        ->extraAttributes([
                            'class' => 'mb-2',
                        ]),

                    TextColumn::make('description')
                        ->html()
                        ->lineClamp(3)
                        ->extraAttributes([
                            'class' => 'text-justify leading-snug',
                            'style' => 'text-align: justify; text-justify: inter-word; margin-top: 0.75rem;',
                        ]),
                    ])
                ])->from('md')
            ])
            ->filters([
                CreatedDateFilter::make('created_at')->columnSpan(2),
                StartDateFilter::make(),
                EndDateFilter::make(),
                SelectFilter::make('founder')
                    ->label('Founder')
                    ->options(Startup::pluck('founder', 'founder'))
                    ->searchable()
                    ->placeholder('All Founders')
                    ->visible(fn () => auth()->user()->hasAnyRole(['super_admin', 'admin'])),
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

                ApproveStartupAction::make()->outlined()->size(Size::ExtraSmall),
                RejectStartupAction::make()->outlined()->size(Size::ExtraSmall),
            ])
            ->headerActions([
                FilamentExportHeaderAction::make('export')
                    ->outlined()
                    ->size(Size::Small)
                    ->color('success')
                    ->fileName('Startups Report')
                    ->defaultFormat('pdf')
                    ->defaultPageOrientation('portrait')
                    ->disableTableColumns()
                    ->withColumns([
                        TextColumn::make('startup_name'),
                        TextColumn::make('founder'),
                        TextColumn::make('members'),
                        TextColumn::make('description'),
                        TextColumn::make('status'),
                        TextColumn::make('admin_comment'),
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
                ->color('gray')
                ->size(Size::Small)
                ->button(),
            ]);
    }
}
