<?php

namespace App\Filament\Portal\Resources\Startups\Schemas;

use App\Models\Startup;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;

use Filament\Support\Enums\Alignment;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;

class StartupForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Startup Form')
                ->schema([
                    TextInput::make('startup_name')
                    ->required()
                    ->unique()
                    ->minLength(2)
                    ->maxLength(255)
                    ->autocapitalize('words')
                    ->placeholder('Enter startup name'),

                TextInput::make('founder')
                    ->required()
                    ->minLength(2)
                    ->maxLength(255)
                    ->autocapitalize('words')
                    ->placeholder('Enter the name of the founder')
                    ->default(fn () => auth()->user()?->hasRole('incubatee') ? auth()->user()?->name : null),

                RichEditor::make('description')
                    ->label('Description')
                    ->columnSpanFull()
                    ->default('<p><em>Enter short description here.</em></p>')
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'underline',
                        'strike',
                        'bulletList',
                        'orderedList',
                        'link',
                    ]),
                Section::make()
                ->footer('Maximum of 4 members excluding founder.')
                ->schema([
                    Repeater::make('members')
                        ->label('Team Members')
                        ->schema([
                            TextInput::make('name')
                                ->label('Member Name')
                                ->placeholder('Enter member name'),
                        ])
                        ->grid(2)
                        ->minItems(0)
                        ->maxItems(4)
                        ->columnSpanFull()
                        ->disableItemMovement()
                        ->addActionAlignment(Alignment::Start)
                        ->createItemButtonLabel('Add Another Member'),
                    ])->columnSpanFull()->compact(),
                ])->columnSpan(2)->columns(2)->compact(),
                
                Grid::make()
                ->schema([
                    Section::make('Logo Upload')
                    ->schema([
                        FileUpload::make('logo')
                            ->label('Startup Logo')
                            ->default(null)
                            
                            //UPLOAD SETTINGS
                            ->image()
                            ->openable()
                            ->imageEditor()
                            ->downloadable()

                            //IMG DIRECTORY
                            ->disk('public')
                            ->directory('startups/logos')
                            ->visibility('public')

                            //IMAGE CROP (1:1)
                            ->imageCropAspectRatio('1:1')
                            ->imageResizeMode('cover')

                            //FILE SIZE LIMIT
                            ->maxSize(5120)
                            ->helperText('Photo file size limit is 5mb. '),
                    ])->columnSpanFull()->compact(),

                    Section::make('Startup Proposal Upload')
                    ->schema([
                        FileUpload::make('document')
                            ->label('PDF Upload')
                            ->default(null)
                            
                            //UPLOAD SETTINGS
                            ->openable()
                            ->appendFiles()
                            ->downloadable()
                            ->acceptedFileTypes(['application/pdf'])

                            //DIRECTORY
                            ->disk('public')
                            ->directory('startups/documents')
                            ->visibility('public')

                            //FILE SIZE LIMIT
                            ->maxSize(16000)
                            ->helperText('Only one PDF upload is allowed (limit 15mb).'),

                        TextInput::make('url')
                            ->url()
                            ->nullable()
                            ->prefix('Link')
                            ->label('Drive Link Upload')
                            ->suffixIcon('heroicon-m-link')
                            ->Helpertext('Upload your videos and powerpoint presentation here.'),
                    ])->columnSpanFull()->compact(),
                ])->columnSpan(1),

                Section::make('Admin Review')
                ->schema([
                    Select::make('status')
                        ->options(Startup::STATUS)
                        ->default('Pending')
                        ->required()
                        ->native(false)
                        ->disabled(fn () => ! auth()->user()->hasAnyRole(['admin', 'super_admin'])),
                    
                    Textarea::make('admin_comment')
                        ->columnSpanFull()
                        ->nullable()
                        ->rows(4),
                ])->columnSpan(2)->columns(2)->compact()->visible(fn () => auth()->user()->hasAnyRole(['admin', 'super_admin'])),
            ])->columns(3);
    }
}
