<?php

namespace App\Filament\Portal\Resources\Users\Tables;

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
use App\Filament\Filters\StartDateFilter;
use App\Filament\Actions\ArchiveBulkAction;
use App\Filament\Filters\CreatedDateFilter;
use Filament\Actions\ForceDeleteBulkAction;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->deferFilters(false)
            ->persistFiltersInSession()
            ->defaultSort('created_at', 'desc')

            ->emptyStateIcon('heroicon-o-user')
            ->emptyStateHeading('No users found')
            ->emptyStateDescription('All users will appear here once its created.')

            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->wrap()
                    ->width('30%')
                    ->verticallyAlignStart(),

                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable()
                    ->icon(Heroicon::Envelope)
                    ->color('success')
                    ->width('10%')
                    ->badge()
                    ->verticallyAlignStart(),

                TextColumn::make('contact')
                    ->searchable()
                    ->icon(Heroicon::Phone)
                    ->width('10%')
                    ->verticallyAlignStart(),

                TextColumn::make('company')
                    ->searchable()
                    ->wrap()
                    ->width('30%')
                    ->verticallyAlignStart(),

                TextColumn::make('roles.name')
                    ->searchable()
                    ->badge()
                    ->color('danger')
                    ->width('10%')
                    ->verticallyAlignStart(),

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->since()
                    ->badge()
                    ->tooltip(fn ($record) => $record->created_at->format('F j, Y g:i A'))
                    ->searchable()
                    ->sortable()
                    ->width('10%')
                    ->color('secondary')
                    ->verticallyAlignStart(),
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
                        ->authorize(fn () => auth()->user()->hasRole('super_admin')),
                    RestoreAction::make()
                        ->color('success')
                        ->authorize(fn () => auth()->user()->hasRole('super_admin')),
                    ArchiveAction::make()
                        ->authorize(fn () => auth()->user()->hasRole('super_admin')),
                    ForceDeleteAction::make()->color('danger')
                        ->icon('heroicon-s-archive-box-x-mark')
                        ->authorize(fn () => auth()->user()->hasRole('super_admin')),
                ])
                ->icon('heroicon-o-bars-3')
                ->color('gray')
                ->size(Size::ExtraSmall)
                ->visible(fn () => auth()->user()->hasRole('super_admin')),

                ViewAction::make('alt_view')
                    ->button()
                    ->color('gray')
                    ->visible(fn () => auth()->user()->hasRole('admin')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    RestoreBulkAction::make()
                        ->color('success')
                        ->authorize(fn () => auth()->user()->hasRole('super_admin')),
                    ArchiveBulkAction::make()->authorize(fn () => auth()->user()->hasRole('super_admin')),
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
