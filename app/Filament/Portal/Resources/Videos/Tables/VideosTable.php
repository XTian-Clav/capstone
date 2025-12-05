<?php

namespace App\Filament\Portal\Resources\Videos\Tables;

use App\Models\Video;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Size;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Actions\ArchiveAction;
use App\Filament\Filters\EndDateFilter;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Enums\FiltersLayout;
use App\Filament\Filters\StartDateFilter;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Filters\TrashedFilter;
use App\Filament\Actions\ArchiveBulkAction;
use App\Filament\Filters\CreatedDateFilter;
use Filament\Actions\ForceDeleteBulkAction;

class VideosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->recordUrl(null)
            ->deferFilters(false)
            ->persistFiltersInSession()
            ->defaultSort('created_at', 'desc')
            
            ->emptyStateIcon('heroicon-o-film')
            ->emptyStateHeading('No videos posted yet')
            ->emptyStateDescription('All videos will appear here once its created.')

            ->contentGrid(['xl' => 2])
            ->columns([
                Split::make([
                    ImageColumn::make('picture')
                        ->label('')
                        ->wrap()
                        ->grow(false)
                        ->disk('public')
                        ->imageHeight(180)
                        ->imageWidth(240)
                        ->defaultImageUrl(url('storage/default/video.png'))
                        ->extraImgAttributes([
                            'alt' => 'Logo',
                            'loading' => 'lazy',
                            'class' => 'rounded-xl object-cover',
                        ])
                        ->url(fn ($record) => $record->url)
                        ->openUrlInNewTab()
                        ->tooltip('Open Video')
                        ->alignCenter(),
                    
                    Stack::make([
                        TextColumn::make('title')->searchable()->weight('semibold')->sortable(),
                        TextColumn::make('description')
                            ->html() 
                            ->lineClamp(3)
                            ->extraAttributes([
                                'class' => 'text-justify leading-snug',
                                'style' => 'text-align: justify; text-justify: inter-word; margin-top: 0.75rem;',
                            ]),

                        TextColumn::make('created_at')
                            ->label('Created At')
                            ->since()
                            ->badge()
                            ->sortable()
                            ->searchable()
                            ->color('secondary')
                            ->extraAttributes(['style' => 'margin-top: 0.75rem;'])
                            ->dateTimeTooltip('F j, Y g:i A'),
                        ]),
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
