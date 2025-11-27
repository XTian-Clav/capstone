<?php

namespace App\Filament\Portal\Resources\Mentors\Schemas;

use App\Models\Mentor;
use App\Models\Startup;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;

class MentorInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Mentor Details')
                ->schema([
                    Grid::make()
                    ->schema([
                        Section::make()
                        ->schema([
                            ImageEntry::make('avatar')
                                ->hiddenLabel()
                                ->alignCenter()
                                ->columnSpan(1)
                                ->disk('public')
                                ->imageHeight(160)
                                ->visibility('public')
                                ->defaultImageUrl(url('storage/default/user.png'))
                                ->extraImgAttributes([
                                    'alt' => 'Logo',
                                    'loading' => 'lazy',
                                    'class' => 'rounded-xl object-cover',
                                ])->columnSpan(1),

                                Section::make()
                                ->schema([
                                    TextEntry::make('name')->label('Name:')->weight('semibold')->inlineLabel(),
                                    TextEntry::make('email')->label('Email:')->weight('semibold')->inlineLabel(),
                                    TextEntry::make('contact')->label('Contact:')->weight('semibold')->inlineLabel(),
                                    TextEntry::make('expertise')->label('Expertise:')->weight('semibold')->inlineLabel(),
                                    
                                    TextEntry::make('created_at')
                                        ->dateTime('F j, Y h:i A')
                                        ->weight('semibold')
                                        ->label('Profile Creation')
                                        ->inlineLabel(),
                                    
                                    TextEntry::make('deleted_at')
                                        ->dateTime('M j, Y h:i A')
                                        ->weight('semibold')
                                        ->color('danger')
                                        ->inlineLabel()
                                        ->visible(fn (Mentor $record): bool => $record->trashed()),
                                ])->columnSpan(2)->secondary()->compact(),
                        ])->columnSpan(3)->columns(3)->compact(),
                        Grid::make()
                        ->schema([
                            Section::make()
                            ->schema([
                                TextEntry::make('personal_info')
                                    ->html()
                                    ->extraAttributes([
                                        'style' => 'text-align: justify; word-break: break-word;',
                                    ]),
                            ])->columnSpanFull()->compact(),
                            Section::make()
                            ->schema([
                                TextEntry::make('schedules')
                                    ->getStateUsing(function ($record) {
                                        $schedules = $record->schedules;
                                        if (!$schedules) return [];
                                
                                        return collect($schedules)->map(function ($item) {
                                            $startupNames = is_array($item['startup'] ?? null)
                                                ? implode(', ', $item['startup'])
                                                : (Startup::find($item['startup'])?->startup_name ?? 'N/A');
                                
                                            return "$startupNames | {$item['day']} {$item['start_time']} - {$item['end_time']}";
                                        })->toArray();
                                    })
                                    ->listWithLineBreaks(),
                            ])->columnSpanFull()->compact(),
                        ])->columnSpan(2),
                    ])->columnSpanFull()->columns(5),
                ])->columns(5)->columnSpanFull()->compact(),
            ]);
    }
}
