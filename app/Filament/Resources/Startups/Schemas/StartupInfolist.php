<?php

namespace App\Filament\Resources\Startups\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class StartupInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('startup_name'),
                TextEntry::make('founder'),
                TextEntry::make('submission_date')
                    ->date(),
                TextEntry::make('status'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
