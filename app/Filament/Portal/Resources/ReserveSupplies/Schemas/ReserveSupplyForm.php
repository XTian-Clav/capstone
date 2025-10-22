<?php

namespace App\Filament\Portal\Resources\ReserveSupplies\Schemas;

use App\Models\Supply;
use App\Models\ReserveSupply;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\Checkbox;
use Filament\Schemas\Components\Text;
use Illuminate\Support\HtmlString;
use Carbon\Carbon;

class ReserveSupplyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Reservation Form')
                ->schema([
                    Section::make()
                    ->schema([
                        TextInput::make('reserved_by')
                            ->label('Reserved By')
                            ->default(fn () => auth()->user()?->name)
                            ->required(),

                        TextInput::make('office')
                            ->label('Office')
                            ->required(),
                        
                        TextInput::make('contact')
                            ->label('Contact')
                            ->required(),
                        
                        TextInput::make('email')
                            ->label('Email')
                            ->default(fn () => auth()->user()?->email)
                            ->required(),
                    ])->columnSpan(2)->columns(2)->compact()->secondary(),

                    Section::make()
                    ->schema([
                        DateTimePicker::make('start_date')
                            ->label('Date Borrowed')
                            ->displayFormat('F j, Y — h:i A')
                            ->required()
                            ->native(false)
                            ->seconds(false)
                            ->rule(function () {
                                return function (string $attribute, $value, $fail) {
                                    $time = Carbon::parse($value); // ✅ convert to Carbon
                                    $hour = $time->format('H');
                        
                                    if ($hour < 8 || $hour >= 18) {
                                        $fail('Reservations are allowed only between 8:00 AM and 6:00 PM.');
                                    }
                                };
                            })
                            ->default(fn () => Carbon::now()->setHour(8)->setMinute(0)->setSecond(0)),

                        DateTimePicker::make('end_date')
                            ->label('Date of Return')
                            ->displayFormat('F j, Y — h:i A')
                            ->required()
                            ->native(false)
                            ->seconds(false)
                            ->rule(function ($get) {
                                return function (string $attribute, $value, $fail) use ($get) {
                                    $end = Carbon::parse($value); // convert to Carbon
                                    $hour = $end->format('H');

                                    if ($hour < 8 || $hour > 18) {
                                        $fail('Reservations are allowed only between 8:00 AM and 6:00 PM.');
                                    }
                        
                                    if ($get('start_date')) {
                                        $start = Carbon::parse($get('start_date'));
                                        if ($end->lessThanOrEqualTo($start)) {
                                            $fail('[Invalid] End time is earlier than the start time.');
                                        }
                                    }
                                };
                            })
                            ->default(fn () => Carbon::now()->setHour(18)->setMinute(0)->setSecond(0)),
                        Text::make('Please adjust the time if needed. Reservations are generally from 8:00 AM to 6:00 PM.')->columnSpanFull(),
                    ])->columnSpan(2)->columns(2)->compact()->secondary(),
                    
                    Section::make()
                    ->collapsed()->description('Terms and Conditions')
                    ->schema([
                        Text::make(new HtmlString('
                            <div style="text-align: justify; font-size: 0.85rem; line-height: 1.5; font-family: monospace;">
                                1. I agree to promptly return the supply borrowed.<br><br>
                                2. I agree to pay for any damage or loss of the supply during the time when the supply is in my possession.<br><br>
                                3. I pledge that the supply I borrowed from PITBI/PSU will be used solely for the official purpose stated above and not for personal purposes.
                            </div>
                        ')),

                        Checkbox::make('accept_terms')
                        ->label('I agree to the Terms and Conditions')
                        ->required()
                        ->rules(['accepted'])
                        ->columnSpan('full'),
                    ])->columnSpanFull()->compact()->secondary(),

                ])->columnSpan(2)->columns(2)->compact(),

                Section::make('Select supply')
                ->schema([
                    Select::make('supply_id')
                        ->hiddenLabel()
                        ->options(Supply::where('quantity', '>', 0)
                                ->pluck('item_name', 'id')
                        )
                        ->searchable()
                        ->required()
                        ->reactive(),

                    // Photo preview
                    Placeholder::make('supply_preview')
                        ->hiddenLabel()
                        ->content(function ($get) {
                            $supply = Supply::find($get('supply_id'));
                            if (! $supply?->picture) return '';
                            $url = Storage::url($supply->picture);
                            return "<div style='max-width:400px;aspect-ratio:16/9'>
                                        <img src='{$url}' class='w-full h-full object-cover rounded-lg border'>
                                    </div>";
                        })
                        ->reactive()
                        ->html(),

                    TextInput::make('quantity')
                        ->numeric()
                        ->default(1)
                        ->minValue(1)
                        ->suffix('pcs')
                        ->required()
                        ->rule(function ($get, $record) {
                            return function (string $attribute, $value, $fail) use ($get, $record) {
                                $supply = Supply::find($get('supply_id'));
                                if (! $supply) return;

                                if ($supply->quantity <= 0) {
                                    $fail("{$supply->item_name} is currently out of stock.");
                                    return;
                                }
                    
                                // Get overlapping APPROVED reservations only
                                $reservedQty = ReserveSupply::query()
                                    ->where('supply_id', $supply->id)
                                    ->where('status', 'Approved')
                                    ->where(function ($q) use ($get, $record) {
                                        $start = $get('start_date');
                                        $end = $get('end_date');
                    
                                        // Overlapping time logic:
                                        //  (A.start <= B.end) && (A.end >= B.start)
                                        $q->where(function ($inner) use ($start, $end) {
                                            $inner->where('start_date', '<=', $end)
                                                  ->where('end_date', '>=', $start);
                                        });
                    
                                        // Exclude current record when editing
                                        if ($record?->exists) {
                                            $q->where('id', '!=', $record->id);
                                        }
                                    })
                                    ->sum('quantity');
                    
                                // Compute available
                                $available = $supply->quantity - $reservedQty;
                    
                                if ($available <= 0) {
                                    $fail("{$supply->item_name} is fully reserved for this time slot.");
                                } elseif ($value > $available) {
                                    $fail("Only {$available} pcs are available for the selected period.");
                                }
                            };
                        }),

                    Select::make('status')
                        ->options(ReserveSupply::STATUS)
                        ->default('Pending')
                        ->required()
                        ->native(false),
                ])->compact(),
            ])->columns(3);
    }
}