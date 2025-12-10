<?php

namespace App\Filament\Portal\Resources\Milestones\Schemas;

use App\Models\Startup;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class MilestoneForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Milestone')
                ->Schema([
                    Grid::make()
                    ->Schema([
                        TextInput::make('title')
                            ->required()
                            ->label('Task')
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->placeholder('Enter title')
                            ->disabled(fn () => ! auth()->user()->hasAnyRole(['admin', 'super_admin'])),
                        
                        Select::make('startup_id')
                            ->label('Startup Name')
                            ->options(fn () => Startup::where('status', 'Approved')->pluck('startup_name', 'id'))
                            ->searchable()
                            ->required()
                            ->columnSpanFull()
                            ->placeholder('Choose startup to assign')
                            ->disabled(fn () => ! auth()->user()->hasAnyRole(['admin', 'super_admin'])),

                        TextInput::make('url')
                            ->url()
                            ->nullable()
                            ->prefix('Link')
                            ->columnSpanFull()
                            ->label('Submission Link for Incubatees')
                            ->suffixIcon('heroicon-m-link')
                            ->placeholder('Enter Google Drive link here')
                            ->disabled(fn () => ! auth()->user()->hasAnyRole(['admin', 'super_admin'])),
                    ])->columnSpan(1),

                    Textarea::make('description')
                        ->label('Description')
                        ->default('Enter description here.')
                        ->columnSpan(2)
                        ->required()
                        ->rows(8)
                        ->disabled(fn () => ! auth()->user()->hasAnyRole(['admin', 'super_admin'])),  
                ])->columns(3)->columnSpanFull()->compact()->visible(fn () => auth()->user()->hasAnyRole(['admin', 'super_admin'])),
                Section::make('Milestone Submission')
                ->schema([
                    Action::make('submission_link')
                        ->link()
                        ->color('success')
                        ->label('View Submission Link')
                        ->icon('heroicon-m-folder-arrow-down')
                        ->url(fn ($record) => $record->url ?? '#')
                        ->openUrlInNewTab()
                        ->tooltip(fn ($record) => $record->url ?? 'No link yet')
                        ->disabled(fn ($record) => !$record?->url),
                            
                    Textarea::make('summary')
                        ->label('Summarize your submission report')
                        ->default('Enter summary here.')
                        ->nullable()
                        ->rows(4)
                        ->disabled(fn () => ! auth()->user()->hasRole('incubatee')),
                ])->columnSpan(2)->compact()->secondary(),

                Section::make()
                ->Schema([
                    Textarea::make('admin_comment')
                        ->rows(4)
                        ->nullable()
                        ->columnSpanFull()
                        ->label('Admin Comment')
                        ->default('Enter comment here.')
                        ->helperText('Admins will leave a comment once your submission is reviewed.')
                        ->disabled(fn () => ! auth()->user()->hasAnyRole(['admin', 'super_admin'])),
                    Toggle::make('is_done')
                        ->label('Mark as Done')
                        ->onColor('success')
                        ->offColor('danger')
                        ->disabled(fn () => ! auth()->user()->hasAnyRole(['admin', 'super_admin']))
                        ->visible(fn () => auth()->user()->hasAnyRole(['admin', 'super_admin'])),
                ])->columnSpan(2)->compact()->secondary(),
            ])->columns(4);
    }
}
