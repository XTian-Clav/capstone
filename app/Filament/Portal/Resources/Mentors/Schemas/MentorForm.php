<?php

namespace App\Filament\Portal\Resources\Mentors\Schemas;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Mentor;
use App\Models\Startup;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use App\Filament\Forms\ScheduleForm;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\MentorResource\Pages;


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
                        ->columnSpanFull()
                        ->placeholder('Enter fullname'),

                    TextInput::make('contact')
                        ->unique()
                        ->required()
                        ->mask('0999-999-9999') // 11 digits
                        ->placeholder('09XX-XXX-XXXX'),
                    
                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(100)
                        ->unique(ignoreRecord: true)
                        ->placeholder('Enter email'),

                    RichEditor::make('personal_info')
                        ->label('Personal Information')
                        ->default('<p><em>Enter your personal info here.</em></p>')
                        ->columnSpanFull()
                        ->toolbarButtons([
                            'bold',
                            'italic',
                            'underline',
                            'bulletList',
                            'orderedList',
                            'link',
                        ]),
                    ScheduleForm::scheduleRepeater()->columnSpanFull()->label('Mentoring Schedules'),
                ])->columnSpan(2)->columns(2)->compact(),
                
                Grid::make()
                ->schema([
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
                    ])->compact(),

                    Section::make('Assigned Startups')
                    ->schema([
                        Select::make('startups')
                            ->hiddenLabel()
                            ->multiple()
                            ->relationship('startups', 'startup_name')
                            ->preload()
                            ->searchable(),
                    ])->compact(),
                ])->columns(1),
            ])->columns(3);
    }
}
