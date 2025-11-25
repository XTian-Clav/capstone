<?php

namespace App\Filament\Portal\Resources\Startups\Schemas;

use App\Models\Mentor;
use App\Models\Startup;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
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
                    ImageEntry::make('logo')
                        ->square()
                        ->hiddenLabel()
                        ->alignCenter()
                        ->columnSpan(1)
                        ->disk('public')
                        ->imageHeight(220)
                        ->visibility('public')
                        ->defaultImageUrl(url('storage/default/no-image.png'))
                        ->extraImgAttributes([
                            'alt' => 'Logo',
                            'loading' => 'lazy',
                            'class' => 'rounded-xl object-cover',
                        ]),
                    
                    Section::make()
                    ->schema([
                        TextEntry::make('startup_name')
                            ->weight('semibold'),
                        
                        TextEntry::make('founder')
                            ->weight('semibold'),
                        Section::make()
                        ->schema([
                            TextEntry::make('description')
                                ->html()
                                ->extraAttributes([
                                    'style' => 'text-align: justify; word-break: break-word;',
                                ])
                                ->columnSpan(3),
                        ])->columnSpanFull()->compact(),
                    ])->columns(5)->columnSpan(4)->compact(),

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
                    ])->columnSpan(1)->compact(),
                    
                    Section::make()
                    ->description('Group Members')
                    ->schema([
                        RepeatableEntry::make('members')
                        ->hiddenLabel()
                        ->schema([
                            TextEntry::make('name')
                            ->hiddenLabel()
                            ->weight('semibold')
                            ->columnSpanFull(),
                        ])->grid(2)->columns(2),
                    ])->columnSpan(2)->compact(),

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
                    ])->columnSpan(2)->compact(),
                ])->columns(5)->columnSpanFull()->compact(),
                Section::make('Admin Review')
                ->schema([
                    TextEntry::make('status')
                        ->label('Startup Status')
                        ->badge()
                        ->colors([
                            'warning' => 'Pending',
                            'success' => 'Approved',
                            'danger' => 'Rejected',
                        ]),
                    TextEntry::make('created_at')
                        ->label('Submission Date')
                        ->since()
                        ->badge()
                        ->color('primary')
                        ->tooltip(fn ($record) => $record->created_at->format('F j, Y g:i A')),

                    TextEntry::make('deleted_at')
                        ->dateTime('M j, Y h:i A')
                        ->badge()
                        ->color('danger')
                        ->visible(fn (Startup $record): bool => $record->trashed()),
                    Section::make()
                    ->schema([
                        TextEntry::make('admin_comment'),
                    ])->columnSpanFull()->compact(),
                ])->columnSpan(3)->columns(4)->compact(),
            ])->columns(5);
    }
}
