<?php

namespace App\Filament\Resources\Mentors\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
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
                    ->toggleable(),

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
                TrashedFilter::make('Archive')->native(false),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                RestoreAction::make()->color('success'),
                DeleteAction::make(),
                ForceDeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    RestoreBulkAction::make(),
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }
}
