<?php

namespace App\Filament\Portal\Resources\Mentors\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

use App\Filament\Resources\MentorResource\Pages;
use App\Models\Mentor;
use App\Models\Startup;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Form;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\RichEditor;

class MentorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Mentor Details')
                ->description('Fill up the form and make sure all details are correct.')
                ->schema([
                    TextInput::make('name')
                        ->unique()
                        ->required()
                        ->minLength(2)
                        ->maxLength(255)
                        ->columnSpanFull(),

                    TextInput::make('contact')
                        ->required()
                        ->unique()
                        ->tel()
                        ->minLength(11)
                        ->helperText('Enter a valid phone number (11 digits).'),
                    
                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(100)
                        ->unique(ignoreRecord: true),
                    
                    RichEditor::make('personal_info')
                        ->label('Personal Information')
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
                
                Section::make('Photo Upload')
                ->schema([
                    FileUpload::make('avatar')
                        ->label('Profile Photo')
                        ->image()
                        ->imageEditor()
                        
                        //IMG DIRECTORY
                        ->disk('public')
                        ->directory('mentors/avatar')
                        ->visibility('public')

                        //IMAGE CROP (1:1)
                        ->imageCropAspectRatio('1:1')
                        ->imageResizeMode('cover')

                        //FILE SIZE LIMIT
                        ->maxSize(5120),

                    Select::make('expertise')
                        ->options(Mentor::EXPERTISE)
                        ->required()
                        ->native(false),
                    
                    Select::make('startups')
                        ->label('Assigned Startups')
                        ->multiple() // since many-to-many
                        ->relationship('startups', 'startup_name')
                        ->preload()
                        ->searchable(),
                ])->compact(),
            ])->columns(3);
    }
}
