<?php

namespace App\Filament\Actions;

use Filament\Actions\Action;

class BackButton extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'back')
            ->label('Back')
            ->icon('heroicon-o-arrow-left')
            ->color('gray')
            ->url(fn ($livewire) => $livewire::getResource()::getUrl('index'));
    }
}
