<?php

namespace App\Filament\Portal\Resources\Videos\Schemas;

use App\Models\Video;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Hugomyb\FilamentMediaAction\Actions\MediaAction;

class VideoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make("")
                ->Schema([
                    MediaAction::make('video')
                        ->label(fn ($record) => $record->title)
                        ->media(fn ($record) => $record->url)
                        ->icon('heroicon-o-play')
                        ->iconButton(),
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
                        ->visible(fn (Video $record): bool => $record->trashed()),
                ])->columnSpanFull(),
            ]);
    }
}
