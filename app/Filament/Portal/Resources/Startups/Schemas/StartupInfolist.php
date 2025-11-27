<?php

namespace App\Filament\Portal\Resources\Startups\Schemas;

use App\Models\Mentor;
use App\Models\Startup;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\Facades\Storage;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Hugomyb\FilamentMediaAction\Actions\MediaAction;

class StartupInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Startup Details')
                ->schema([
                    Grid::make()
                    ->schema([
                        Section::make()
                        ->schema([
                            ImageEntry::make('logo')
                                ->square()
                                ->hiddenLabel()
                                ->alignLeft()
                                ->disk('public')
                                ->imageHeight(170)
                                ->visibility('public')
                                ->defaultImageUrl(url('storage/default/no-image.png'))
                                ->extraImgAttributes([
                                    'alt' => 'Logo',
                                    'loading' => 'lazy',
                                    'class' => 'rounded-xl object-cover',
                                ])
                                ->columnSpan(1),

                            Grid::make()
                            ->schema([
                                TextEntry::make('startup_name')
                                ->hiddenLabel()
                                ->weight('semibold')
                                ->columnSpanFull(),

                                TextEntry::make('description')
                                    ->html()
                                    ->hiddenLabel()
                                    ->extraAttributes([
                                        'style' => 'text-align: justify; word-break: break-word;',
                                    ])
                                    ->columnSpanFull(),
                            ])->columnSpan(2),
                        ])->columns(3)->columnSpanFull()->compact(),
                        
                        Section::make()
                        ->description('Mentors & Schedules')
                        ->schema([
                            TextEntry::make('mentors_schedules')
                                ->hiddenLabel()
                                ->getStateUsing(function ($record) {
                                    if (!$record) return [];

                                    return Mentor::get()
                                        ->map(function ($mentor) use ($record) {
                                            $schedules = [];

                                            foreach ($mentor->schedules ?? [] as $s) {
                                                if (($s['startup'] ?? null) === $record->id) {
                                                    $schedules[] = "{$s['day']} {$s['start_time']} - {$s['end_time']}";
                                                }
                                            }
                                            if (empty($schedules)) return null;
                                            return "{$mentor->name} | " . implode('; ', $schedules);
                                        })
                                        ->filter()
                                        ->values()
                                        ->toArray();
                                })
                                ->listWithLineBreaks(),
                        ])->columnSpanFull()->compact(),

                        Section::make()
                        ->description('Admin Review')
                        ->schema([
                            Grid::make()
                            ->schema([
                                TextEntry::make('status')
                                    ->label('Startup Status:')
                                    ->badge()
                                    ->colors([
                                        'warning' => 'Pending',
                                        'success' => 'Approved',
                                        'danger' => 'Rejected',
                                    ]),
                                TextEntry::make('created_at')
                                    ->label('Submission Date:')
                                    ->since()
                                    ->badge()
                                    ->color('primary')
                                    ->tooltip(fn ($record) => $record->created_at->format('F j, Y g:i A')),

                                TextEntry::make('deleted_at')
                                    ->dateTime('M j, Y h:i A')
                                    ->badge()
                                    ->color('danger')
                                    ->visible(fn (Startup $record): bool => $record->trashed()),
                                ])->columnSpanFull()->columns(4),
                            Section::make()
                            ->schema([
                                TextEntry::make('admin_comment'),
                            ])->columnSpanFull()->compact(),
                        ])->columnSpanFull()->compact()->visible(fn (Startup $record) => $record->status === 'Rejected'),
                    ])->ColumnSpan(2),

                    Grid::make()
                    ->schema([
                        Section::make()
                        ->description('Startup Proposal Plan')
                        ->schema([
                            MediaAction::make('document')
                                ->media(fn ($record) => Storage::url($record->document))
                                ->icon('heroicon-m-document-arrow-down')
                                ->mediaType(MediaAction::TYPE_PDF)
                                ->label('View PDF file')
                                ->color('success')
                                ->link(),

                            Action::make('view_drive_link')
                                ->label('View Drive Link')
                                ->icon('heroicon-m-folder-arrow-down')
                                ->color('success')
                                ->link()
                                ->url(fn ($record) => $record->url)
                                ->openUrlInNewTab()
                                ->tooltip(fn ($record) => $record->url),  
                        ])->columnSpanFull()->compact(),

                        Section::make()
                        ->schema([
                            TextEntry::make('founder')
                                ->badge()
                                ->label('Founder:')
                                ->color('primary'),
                            
                            RepeatableEntry::make('members')
                                ->label('Members:')
                                ->schema([
                                    TextEntry::make('name')
                                        ->badge()
                                        ->hiddenLabel()
                                        ->color('primary')
                                        ->columnSpanFull(),
                                ])->contained(false),
                        ])->ColumnSpanFull()->compact(),
                    ])->columnSpan(1),
                ])->columns(3)->columnSpanFull()->compact(),
            ])->columns(3);
    }
}
