<?php

namespace App\Filament\Filters;

use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;

class StartDateFilter
{
    public static function make(string $column = 'created_at')
    {
        return Filter::make('start_date')
            ->label('Start Date')
            ->schema([
                DatePicker::make('start_date')
                    ->label('Start Date')
                    ->native(false),
            ])
            ->query(function (Builder $query, array $data) use ($column): Builder {
                return $query->when(
                    $data['start_date'] ?? null,
                    fn (Builder $query, $date): Builder => $query->whereDate($column, '>=', $date),
                );
            });
    }
}