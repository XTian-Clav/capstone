<?php

namespace App\Filament\Filters;

use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;

class EndDateFilter
{
    public static function make(string $column = 'created_at')
    {
        return Filter::make('end_date')
            ->label('End Date')
            ->schema([
                DatePicker::make('end_date')
                    ->label('End Date')
                    ->native(false)
                    ->placeholder('Select End Date'),
            ])
            ->query(function (Builder $query, array $data) use ($column): Builder {
                return $query->when(
                    $data['end_date'] ?? null,
                    fn (Builder $query, $date): Builder => $query->whereDate($column, '<=', $date),
                );
            });
    }
}