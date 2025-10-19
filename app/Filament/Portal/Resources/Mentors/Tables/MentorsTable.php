<?php

namespace App\Filament\Portal\Resources\Mentors\Tables;

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
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MentorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->defaultSort('created_at', 'asc')
            ->columns([
                ImageColumn::make('avatar')
                    ->label('Photo')
                    ->disk('public')
                    ->size(50)
                    ->circular()
                    ->toggleable()
                    ->defaultImageUrl(url('storage/default/user.png')),

                TextColumn::make('name')
                    ->weight('semibold')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('contact')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->icon(Heroicon::Envelope),
                
                TextColumn::make('expertise')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                BadgeColumn::make('startups.startup_name')
                    ->label('Assigned To')
                    ->separator(', ')
                    ->sortable()
                    ->toggleable()
                    ->default('None')
                    ->color(fn ($state) => $state === 'None' ? 'gray' : 'info'),
                
                TextColumn::make('created_at')
                    ->dateTime('m-d-y g:i A')
                    ->tooltip(fn ($record) => $record->created_at->format('F j, Y g:i A'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->dateTime('M j, Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('deleted_at')
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
                //TrashedFilter::make('Archive')->native(false),
            ], layout: FiltersLayout::Modal)
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()->color('secondary'),
                    EditAction::make()->color('secondary')
                        ->visible(fn ($record) => ! $record->trashed())
                        ->authorize(fn () => auth()->user()->hasAnyRole(['admin', 'super_admin'])),
                    RestoreAction::make()
                        ->color('success')
                        ->authorize(fn () => auth()->user()->hasRole('super_admin')),
                    DeleteAction::make()->color('danger')
                        ->icon('heroicon-s-archive-box-arrow-down')
                        ->label('Archive')
                        ->authorize(fn () => auth()->user()->hasAnyRole(['admin', 'super_admin'])),
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
                    RestoreBulkAction::make()->color('success'),
                    DeleteBulkAction::make()
                        ->label('Archive')
                        ->color('secondary')
                        ->icon('heroicon-s-archive-box-arrow-down'),
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
