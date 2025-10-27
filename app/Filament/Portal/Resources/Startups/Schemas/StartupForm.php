<?php

namespace App\Filament\Portal\Resources\Startups\Schemas;

use App\Models\Startup;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Storage;
use Filament\Schemas\Components\Section;
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
                    ->maxLength(255),

                TextInput::make('founder')
                    ->required()
                    ->minLength(2)
                    ->maxLength(255)
                    ->default(fn () => auth()->user()?->hasRole('incubatee') ? auth()->user()?->name : null),
                
                RichEditor::make('description')
                    ->label('Description')
                    ->default(null)
                    ->columnSpanFull()
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
                    ]),
                ])->columnSpan(2)->columns(2)->compact(),

                Section::make('Logo Upload')
                ->schema([
                    FileUpload::make('logo') ->label('Startup Logo')
                        ->label('Startup Logo')
                        ->default(null)
                        ->image()
                        ->imageEditor()

                        //IMG DIRECTORY
                        ->disk('public')
                        ->directory('startups/logos')
                        ->visibility('public')

                        //IMAGE CROP (1:1)
                        ->imageCropAspectRatio('1:1')
                        ->imageResizeMode('cover')

                        //FILE SIZE LIMIT
                        ->maxSize(5120),
                    
                    Select::make('status')
                        ->options(Startup::STATUS)
                        ->default('Pending')
                        ->required()
                        ->native(false)
                        ->disabled(fn () => ! auth()->user()->hasAnyRole(['admin', 'super_admin'])),
                ])->compact(),
            ])->columns(3);
    }
}
