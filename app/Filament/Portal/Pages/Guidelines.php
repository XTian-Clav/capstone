<?php

namespace App\Filament\Portal\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;

class Guidelines extends Page
{
    protected string $view = 'filament.portal.pages.guidelines';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print_report')
                ->color('info')
                ->label('Print')
                ->icon('heroicon-s-printer')
                ->url(route('PrintGuidelines')),
        ];
    }
}
