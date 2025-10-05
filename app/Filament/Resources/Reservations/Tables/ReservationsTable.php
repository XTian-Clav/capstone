<?php

namespace App\Filament\Resources\Reservations\Tables;

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

class ReservationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->defaultSort('submission_date', 'asc')
            ->columns([
                TextColumn::make('reservation')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('reservation_type')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('quantity')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('borrower')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('purpose')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('submission_date')
                    ->date()
                    ->sortable()
                    ->toggleable(),

                BadgeColumn::make('status')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->colors([
                        'warning' => 'Pending',
                        'success' => 'Approved',
                        'danger' => 'Rejected',
                    ]),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                ->options([
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                ])
                ->label('Status'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
