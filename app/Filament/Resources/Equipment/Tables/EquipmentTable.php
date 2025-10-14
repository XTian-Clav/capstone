<?php

namespace App\Filament\Resources\Equipment\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\Action;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Enums\FiltersLayout;

class EquipmentTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->defaultSort('created_at', 'asc')
            ->columns([
                TextColumn::make('equipment_name')
                    ->searchable()
                    ->toggleable()
                    ->sortable()
                    ->weight('semibold'),

                TextColumn::make('quantity')
                    ->numeric()
                    ->searchable()
                    ->toggleable()
                    ->sortable()
                    ->state(fn ($record) => 
                        $record->quantity === 0 ? 'Out of Stock':
                        $record->quantity . ' ' . ($record->quantity === 1 ? 'pc' : 'pcs')
                    ),
                    
                TextColumn::make('property_no')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),

                TextColumn::make('location')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),

                TextColumn::make('remarks')
                    ->searchable()
                    ->toggleable()
                    ->sortable()
                    ->weight('semibold'),
                
                TextColumn::make('created_at')
                    ->dateTime('M j, Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

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
            ], layout: FiltersLayout::Modal)
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
