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
                    ->label('Quick Filter')
                    ->options([
                        'today' => 'Today',
                        'this_week' => 'This Week',
                        'this_month' => 'This Month',
                        'last_month' => 'Last Month',
                    ])
                    ->inline(),
            ])
            ->query(function (Builder $query, array $data) use ($column): Builder {
                return match ($data['preset'] ?? null) {
                    'today' => $query->whereBetween($column, [
                        now()->startOfDay(), 
                        now()->endOfDay()
                    ]),
                    
                    'this_week' => $query->whereBetween($column, [
                        now()->startOfWeek(), 
                        now()->endOfWeek()
                    ]), 
                    
                    'this_month' => $query->whereBetween($column, [
                        now()->startOfMonth(), 
                        now()->endOfMonth()
                    ]), 
                    
                    'last_month' => $query->whereBetween($column, [
                        now()->subMonth()->startOfMonth(), 
                        now()->subMonth()->endOfMonth()
                    ]),
                    
                    default => $query,
                };
            });
    }
}