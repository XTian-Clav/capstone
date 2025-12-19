<?php

namespace App\Filament\Portal\Resources\UnavailableSupplies\Schemas;

use Closure;
use App\Models\Supply;
use Filament\Schemas\Schema;
use App\Models\UnavailableSupply;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;

class UnavailableSupplyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Supply Status Report')
                ->schema([
                    Select::make('supply_id')
                        ->label('Choose supply')
                        ->placeholder('Select supply')
                        ->options(Supply::where('quantity', '>', 0)->pluck('item_name', 'id'))
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
                                
                                $supplyId = $get('supply_id');
                                if (!$supplyId) {
                                    return $fail("Please select the supply first.");
                                }
    
                                $supply = supply::find($supplyId);
                                if (!$supply) {
                                    return $fail("Selected supply not found.");
                                }
                                
                                $currentUnavailableInReport = $operation === 'edit' ? $record->getOriginal('unavailable_quantity') : 0;
                                
                                $netChangeRequired = $value - $currentUnavailableInReport; 
    
                                if ($netChangeRequired > 0) {
                                    if ($supply->quantity < $netChangeRequired) {
                                        $fail("Insufficient stock. Only {$supply->quantity} item(s) are currently available.");
                                    }
                                }
                            };
                        }),

                    Select::make('status')
                        ->options(UnavailableSupply::STATUS)
                        ->default('Unavailable')
                        ->required()
                        ->native(false)
                        ->disabled(fn () => ! auth()->user()->hasAnyRole(['admin', 'super_admin'])),
                    
                    Textarea::make('remarks')
                        ->required()
                        ->columnSpanFull()
                        ->placeholder('Describe the supplys condition.')
                        ->rows(6),
                ])->columnSpan(2)->columns(2)->compact(),

                Section::make('Photo Upload')
                ->schema([
                    FileUpload::make('picture')
                    ->label('Item Photo')
                    ->columnSpanFull()
                    ->image()
                    ->imageEditor()
                    
                    //IMG DIRECTORY
                    ->disk('public')
                    ->directory('unavailable_supply/pictures')
                    ->visibility('public')

                    //FILE SIZE LIMIT
                    ->maxSize(10000),
                ])->columnSpan(1)->columns(2)->compact(),
            ])->columns(3);
    }
}
