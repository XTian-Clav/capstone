<?php
 
namespace App\Filament\Pages;

use ShuvroRoy\FilamentSpatieLaravelBackup\Pages\Backups as BaseBackups;
use Illuminate\Contracts\Support\Htmlable;

class Backups extends BaseBackups
{
    protected static \BackedEnum|string|null $navigationIcon = '';

    public function getHeading(): string|Htmlable
    {
        return 'Application Backups';   
    }

    public static function getNavigationGroup(): ?string
    {
        return 'System Settings';
    }
}
