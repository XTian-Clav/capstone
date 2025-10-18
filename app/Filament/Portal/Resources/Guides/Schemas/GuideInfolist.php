<?php

namespace App\Filament\Portal\Resources\Guides\Schemas;

use App\Models\Guide;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class GuideInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title'),
                TextEntry::make('description'),
                TextEntry::make('url'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Guide $record): bool => $record->trashed()),
            ]);
    }
}
