<?php

namespace App\Filament\Portal\Resources\Videos\Schemas;

use App\Models\Video;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;

class VideoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make("")
                ->Schema([
                    Section::make()
                    ->Schema([
                        TextEntry::make('title')->weight('semibold')->columnSpan(2),
                        
                        TextEntry::make('created_at')
                            ->dateTime('M j, Y h:i A')
                            ->weight('semibold')
                            ->color('secondary')
                            ->badge(),
                        
                        TextEntry::make('updated_at')
                            ->dateTime('M j, Y h:i A')
                            ->weight('semibold')
                            ->color('secondary')
                            ->badge(),
                    ])->columns(4)->columnSpanFull(),
                    Section::make()
                    ->Schema([
                        TextEntry::make('description')
                            ->columnSpanFull()
                            ->html()
                            ->extraAttributes([
                                'style' => 'text-align: justify; white-space: pre-line; word-break: break-word;',
                            ]),
                        
                        TextEntry::make('url')
                            ->label('Video Link')
                            ->color('info')
                            ->url(fn (Video $record) => $record->url)
                            ->openUrlInNewTab(),
                        
                        TextEntry::make('deleted_at')
                            ->dateTime('M j, Y h:i A')
                            ->weight('semibold')
                            ->color('danger')
                            ->visible(fn (Video $record): bool => $record->trashed()),
                    ])->columns(2)->columnSpanFull(),
                ])->columns(2)->columnSpanFull()->secondary(),
            ]);
    }
}
