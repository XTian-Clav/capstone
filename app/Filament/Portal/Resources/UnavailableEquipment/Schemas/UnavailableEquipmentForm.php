<?php

namespace App\Filament\Portal\Resources\UnavailableEquipment\Schemas;

use Closure;
use App\Models\Equipment;
use Filament\Schemas\Schema;
use App\Models\UnavailableEquipment;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;

class UnavailableEquipmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Equipment Status Report')
                ->schema([
                    Select::make('equipment_id')
                        ->label('Choose Equipment')
                        ->placeholder('Select equipment')
                        ->options(Equipment::where('quantity', '>', 0)->pluck('equipment_name', 'id'))
                        ->disabled(fn ($record, $context) => $context === 'edit')
                        ->searchable()
                        ->required(),
                    
                    TextInput::make('unavailable_quantity')
                        ->live()
                        ->required()
                        ->numeric()
                        ->default(0)
                        ->minValue(1)
                        ->suffix('pcs')
                        ->label('Unavailable Quantity')
                        ->rule(function (string $operation, ?Model $record, $state, $get) {
                            return function (string $attribute, $value, Closure $fail) use ($operation, $record, $state, $get) {
                                
                                $equipmentId = $get('equipment_id');
                                if (!$equipmentId) {
                                    return $fail("Please select the Equipment first.");
                                }
    
                                $equipment = Equipment::find($equipmentId);
                                if (!$equipment) {
                                    return $fail("Selected equipment not found.");
                                }
                                
                                $currentUnavailableInReport = $operation === 'edit' ? $record->getOriginal('unavailable_quantity') : 0;
                                
                                $netChangeRequired = $value - $currentUnavailableInReport; 
    
                                if ($netChangeRequired > 0) {
                                    if ($equipment->quantity < $netChangeRequired) {
                                        $fail("Insufficient stock. Only {$equipment->quantity} item(s) are currently available.");
                                    }
                                }
                            };
                        }),

                    Select::make('status')
                        ->options(UnavailableEquipment::STATUS)
                        ->default('Unavailable')
                        ->required()
                        ->native(false)
                        ->disabled(fn () => ! auth()->user()->hasAnyRole(['admin', 'super_admin'])),
                    Textarea::make('remarks')
                        ->required()
                        ->columnSpanFull()
                        ->placeholder('Describe the equipments condition.')
                        ->rows(6),
                ])->columnSpan(2)->columns(2)->compact(),

                Section::make('Photo Upload')
                ->schema([
                    FileUpload::make('picture')
                    ->label('Equipment Photo')
                    ->columnSpanFull()
                    ->image()
                    ->imageEditor()
                    
                    //IMG DIRECTORY
                    ->disk('public')
                    ->directory('unavailable_equipment/pictures')
                    ->visibility('public')

                    //FILE SIZE LIMIT
                    ->maxSize(10000),
                ])->columnSpan(1)->columns(2)->compact(),
            ])->columns(3);
    }
}
