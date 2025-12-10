<?php

namespace App\Filament\Portal\Resources\Announcements\Tables;

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
use Filament\Tables\Columns\Layout\Stack;
use App\Filament\Filters\CreatedDateFilter;

class AnnouncementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->deferFilters(false)
            ->persistFiltersInSession()
            ->defaultSort('created_at', 'desc')

            ->emptyStateIcon('heroicon-o-megaphone')
            ->emptyStateHeading('No announcements posted yet')
            ->emptyStateDescription('All announcements will appear here once its created.')

            ->contentGrid(['xl' => 3])
            ->columns([
                Stack::make ([
                    TextColumn::make('title')->searchable()->weight('semibold')->sortable(), 
                    
                    TextColumn::make('content')
                        ->html()
                        ->extraAttributes(['style' => 'margin-top: 0.75rem;']),
                
                    TextColumn::make('created_at')
                        ->label('Created At')
                        ->since()
                        ->badge()
                        ->sortable()
                        ->searchable()
                        ->extraAttributes(['style' => 'margin-top: 0.75rem;'])
                        ->dateTimeTooltip('F j, Y g:i A'),
                ])->space(1)
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
                ->label('Actions')
                ->icon('heroicon-o-bars-arrow-down')
                ->color('gray')
                ->size(Size::ExtraSmall)
                ->button()
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
