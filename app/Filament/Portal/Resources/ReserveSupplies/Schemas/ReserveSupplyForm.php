<?php

namespace App\Filament\Portal\Resources\ReserveSupplies\Schemas;

use Carbon\Carbon;
use App\Models\Supply;
use Filament\Schemas\Schema;
use App\Models\ReserveSupply;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Text;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Placeholder;
use App\Filament\Forms\ReservationValidation;
use Filament\Forms\Components\DateTimePicker;

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
                            ->placeholder('Enter reserver name')
                            ->default(fn () => auth()->user()?->hasRole('incubatee') ? auth()->user()?->name : null)
                            ->required(),

                        TextInput::make('company')
                            ->label('Company')
                            ->placeholder('Select office or company name')
                            ->default(fn () => auth()->user()?->hasRole('incubatee') ? auth()->user()?->company : null)
                            ->required(),
                        
                        TextInput::make('contact')
                            ->label('Contact')
                            ->mask('0999-999-9999')
                            ->placeholder('09XX-XXX-XXXX')
                            ->default(fn () => auth()->user()?->hasRole('incubatee') ? auth()->user()?->contact : null)
                            ->required(),
                        
                        TextInput::make('email')
                            ->label('Email')
                            ->placeholder('enter reserver email')
                            ->default(fn () => auth()->user()?->hasRole('incubatee') ? auth()->user()?->email : null)
                            ->required(),
                    ])->columnSpan(2)->columns(2)->compact()->secondary(),

                    Section::make()
                    ->schema([
                        ReservationValidation::startDate('supply_id', ReserveSupply::class),
                        ReservationValidation::endDate('supply_id', ReserveSupply::class),
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
                        ->placeholder('Select supply')
                        ->options(Supply::where('quantity', '>', 0)
                                ->pluck('item_name', 'id')
                        )
                        ->searchable()
                        ->required()
                        ->reactive(),
                    
                    Placeholder::make('supply_details')
                        ->hiddenLabel()
                        ->content(function ($get) {
                            $supply = Supply::find($get('supply_id'));
                            if (! $supply) return '';
                            $html = '';

                            // supply preview image
                            if ($supply->picture) {
                                $url = Storage::url($supply->picture);
                                $html .= "<div style='max-width:400px;aspect-ratio:16/9;margin-bottom:0.5rem;'>
                                            <img src='{$url}' style='width:100%;height:100%;object-fit:cover;border-radius:0.5rem;'>
                                        </div>";
                            }

                            // Stock badge
                            $html .= "<div style='display:inline-block;background-color:#013267;color:white;padding:0.25rem 0.5rem;border-radius:0.5rem;'>
                                        Stock: {$supply->quantity}
                                    </div>";

                            return $html;
                        })
                        ->reactive()
                        ->html(),

                    ReservationValidation::supplyQuantity(),
                ])->compact(),

                Section::make('Admin Review')
                ->schema([
                    Select::make('status')
                        ->options(ReserveSupply::STATUS)
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