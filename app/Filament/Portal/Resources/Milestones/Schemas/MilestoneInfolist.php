<?php

namespace App\Filament\Portal\Resources\Milestones\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;

class MilestoneInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Milestone Task')
                ->schema([
                    Grid::make(3)
                    ->schema([
                        TextEntry::make('startup.startup_name')
                            ->hiddenLabel()    
                            ->weight('semibold')
                            ->columnSpan(2),

                        TextEntry::make('is_done')
                            ->badge()
                            ->hiddenLabel()
                            ->state(fn ($record) => $record->is_done ? 'Done' : 'Not Done')
                            ->color(fn ($record) => $record->is_done ? 'success' : 'danger')
                            ->weight('semibold'),
                        TextEntry::make('created_at')
                            ->badge()
                            ->hiddenLabel()
                            ->color('secondary')
                            ->formatStateUsing(fn ($state) => $state?->format('M j, Y'))
                            ->tooltip(fn ($state) => $state?->format('M j, Y h:i A')),
                    ])->columns(4)->columnSpanFull(),
                    Section::make()
                    ->Schema([
                        TextEntry::make('title')
                            ->hiddenLabel()
                            ->weight('semibold'), 
                        TextEntry::make('description')
                            ->html()
                            ->hiddenLabel()
                            ->columnSpanFull()
                            ->extraAttributes([
                                'style' => 'text-align: justify; white-space: pre-line; word-break: break-word;',
                            ]),
                    ])->columnSpan(2)->compact()->secondary(),
                ])->columns(2)->columnSpan(2)->compact(),
                Section::make('Admin Comments')
                    ->Schema([
                        Section::make()
                        ->schema([
                            TextEntry::make('admin_comments')
                            ->html()
                            ->hiddenLabel()
                            ->default('<p><em>No Comments Yet.</em></p>')
                            ->columnSpanFull()
                            ->extraAttributes([
                                'style' => 'text-align: justify; white-space: pre-line; word-break: break-word;',
                            ]),
                        ])->secondary(),
                ])->columnSpan(1)->compact(),
            ])->columns(3);
    }
}
