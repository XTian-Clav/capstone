<?php

namespace App\Filament\Filters;

use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Radio;
use Illuminate\Database\Eloquent\Builder;

class CreatedDateFilter
{
    public static function make(string $column = 'created_at', string $label = 'Created')
    {
        return Filter::make($column)
            ->label($label)
            ->schema([
                Radio::make('preset')
                    ->label('Quick Filters')
                    ->options([
                        'today' => 'Today',
                        'last_week' => 'Last Week',
                        'last_month' => 'Last Month',
                        'last_year' => 'Last Year',
                    ])
                    ->inline(),
            ])
            ->query(function (Builder $query, array $data) use ($column): Builder {
                return match ($data['preset'] ?? null) {
                    'today' => $query->whereBetween($column, [now()->startOfDay(), now()->endOfDay()]),
                    'last_week' => $query->whereBetween($column, [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()]),
                    'last_month' => $query->whereBetween($column, [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()]),
                    'last_year' => $query->whereBetween($column, [now()->subYear()->startOfYear(),now()->subYear()->endOfYear()]),
                    default => $query,
                };
            });
    }
}
