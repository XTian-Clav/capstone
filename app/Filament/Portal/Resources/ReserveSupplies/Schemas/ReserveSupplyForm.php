<?php

namespace App\Filament\Portal\Resources\ReserveSupplies\Schemas;

use App\Models\Supply;
use Filament\Schemas\Schema;
use App\Models\ReserveSupply;
use App\Filament\Actions\SupplyTerms;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Text;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Placeholder;
use App\Filament\Forms\ReservationValidation;

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
                            ->readOnly(fn ($record, $context) => $context === 'edit')
                            ->default(fn () => auth()->user()?->hasRole('incubatee') ? auth()->user()?->name : null)
                            ->required(),

                        TextInput::make('company')
                            ->label('Company')
                            ->placeholder('Select office or company name')
                            ->readOnly(fn ($record, $context) => $context === 'edit')
                            ->default(fn () => auth()->user()?->hasRole('incubatee') ? auth()->user()?->company : null)
                            ->required(),
                        
                        TextInput::make('contact')
                            ->label('Contact')
                            ->mask('0999-999-9999')
                            ->placeholder('09XX-XXX-XXXX')
                            ->readOnly(fn ($record, $context) => $context === 'edit')
                            ->default(fn () => auth()->user()?->hasRole('incubatee') ? auth()->user()?->contact : null)
                            ->required(),
                        
                        TextInput::make('email')
                            ->label('Email')
                            ->placeholder('enter reserver email')
                            ->readOnly(fn ($record, $context) => $context === 'edit')
                            ->default(fn () => auth()->user()?->hasRole('incubatee') ? auth()->user()?->email : null)
                            ->required(),
                    ])->columnSpan(2)->columns(2)->compact(),

                    Section::make()
                    ->schema([
                        ReservationValidation::startDate('supply_id', ReserveSupply::class),
                        ReservationValidation::endDate('supply_id', ReserveSupply::class),
                        Text::make('Please adjust the time if needed. Reservations are generally from 8:00 AM to 6:00 PM.')->columnSpanFull(),
                    ])->columnSpan(2)->columns(2)->compact(),
                ])->columnSpan(2)->columns(2)->compact(),
                
                Grid::make()
                ->schema([
                    Section::make('Select supply')
                    ->schema([
                        Select::make('supply_id')
                            ->hiddenLabel()
                            ->placeholder('Select supply')
                            ->options(Supply::where('quantity', '>', 0)->pluck('item_name', 'id'))
                            ->disabled(fn ($record, $context) => $context === 'edit')
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

                                $html .= "<div style='margin-top:0.5rem; font-weight:600;'>
                                            Available: {$supply->quantity} pcs
                                        </div>";

                                return $html;
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
                                return function ($attribute, $value, $fail) use ($get, $record) {
                                    $supply = Supply::find($get('supply_id'));
                                    if (! $supply) return;
                        
                                    $available = $supply->quantity;

                                    if ($record?->status === 'Approved') {
                                        $available += $record->quantity;
                                    }
                        
                                    if ($value > $available) {
                                        $fail("Only {$available} pcs are available for the selected supply.");
                                    }
                                };
                            }),
                    ])->columnSpanFull()->compact(),

                    Section::make()
                    ->schema([
                        SupplyTerms::make(),
                    ])->columnSpanFull()->compact(),
                ])->columnSpan(1),

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