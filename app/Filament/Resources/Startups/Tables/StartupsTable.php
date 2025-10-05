<?php

namespace App\Filament\Resources\Startups\Tables;

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

class StartupsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->defaultSort('startup_name', 'asc')
            ->columns([
                ImageColumn::make('logo')
                    ->label('Logo')
                    ->disk('public')
                    ->size(50)
                    ->rounded()
                    ->toggleable(),

                TextColumn::make('startup_name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('founder')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('submission_date')
                    ->dateTime('F j, Y h:i A')
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
