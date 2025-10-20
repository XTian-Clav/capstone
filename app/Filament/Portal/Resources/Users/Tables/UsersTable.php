<?php

namespace App\Filament\Portal\Resources\Users\Tables;

use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Size;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Tables\Filters\Filter;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Actions\ArchiveAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use App\Filament\Actions\ArchiveBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),

                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable()
                    ->toggleable()
                    ->icon(Heroicon::Envelope),

                TextColumn::make('contact')
                    ->searchable()
                    ->toggleable()
                    ->icon(Heroicon::Phone),

                TextColumn::make('roles.name')
                    ->searchable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('created_at')
                    ->dateTime('M j, Y h:i A')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->dateTime('M j, Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('today')
                    ->toggle()
                    ->label('Created Today')
                    ->query(fn ($query, $state) => 
                        $state
                            ? $query->whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])
                                    ->reorder('created_at', 'asc')
                            : null
                    ),
                //TrashedFilter::make()->native(false),
            ])
            ->recordActions([
                //Admin have limited access to Users Table
                ActionGroup::make([
                    ViewAction::make()->color('secondary'),
                    EditAction::make()->color('secondary')
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
                ->label('Actions')
                ->icon('heroicon-o-bars-arrow-down')
                ->color('secondary')
                ->size(Size::ExtraSmall)
                ->button(),
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
