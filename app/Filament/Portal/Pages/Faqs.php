<?php

namespace App\Filament\Portal\Pages;

use Filament\Pages\Page;

class Faqs extends Page
{
    protected string $view = 'filament.portal.pages.faqs';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
