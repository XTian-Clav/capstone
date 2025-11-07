<?php

namespace App\Filament\Portal\Resources\Milestones\Schemas;

use App\Models\Startup;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\RichEditor;

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
                            ->options(Startup::pluck('startup_name', 'id'))
                            ->searchable()
                            ->required()
                            ->columnSpanFull()
                            ->placeholder('Choose startup to assign')
                            ->disabled(fn () => ! auth()->user()->hasAnyRole(['admin', 'super_admin'])),
                    ])->columnSpan(1),

                    RichEditor::make('description')
                        ->label('Description')
                        ->default('<p><em>Enter description here.</em></p>')
                        ->columnSpan(2)
                        ->toolbarButtons([
                            'bold',
                            'italic',
                            'underline',
                            'strike',
                            'bulletList',
                            'orderedList',
                            'link',
                            'undo',
                            'redo',
                        ])
                        ->nullable()
                        ->required()
                        ->disabled(fn () => ! auth()->user()->hasAnyRole(['admin', 'super_admin'])),  
                ])->columns(3)->columnSpanFull()->compact()->visible(fn () => auth()->user()->hasAnyRole(['admin', 'super_admin'])),
                Section::make('Milestone Submission')
                ->schema([
                    TextInput::make('url')
                    ->url()
                    ->nullable()
                    ->prefix('Link')
                    ->label('Upload Link')
                    ->suffixIcon('heroicon-m-link')
                    ->placeholder('Enter Google Drive link here')
                    ->disabled(fn () => ! auth()->user()->hasRole('incubatee')),

                    RichEditor::make('summary')
                        ->label('Summarize your submission report')
                        ->default('<p><em>Enter summary here.</em></p>')
                        ->toolbarButtons([
                            'bold',
                            'italic',
                            'underline',
                            'strike',
                            'bulletList',
                            'orderedList',
                            'link',
                            'undo',
                            'redo',
                        ])
                        ->nullable()
                        ->disabled(fn () => ! auth()->user()->hasRole('incubatee')),
                ])->columnSpan(2)->compact()->secondary(),

                Section::make()
                ->Schema([
                    RichEditor::make('admin_comment')
                        ->label('Admin Comment')
                        ->default('<p><em>Enter comment here.</em></p>')
                        ->toolbarButtons([
                            'bold',
                            'italic',
                            'underline',
                            'strike',
                            'bulletList',
                            'orderedList',
                            'link',
                            'undo',
                            'redo',
                        ])
                        ->nullable()
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
