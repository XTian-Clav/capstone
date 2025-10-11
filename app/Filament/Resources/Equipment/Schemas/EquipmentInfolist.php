<?php

namespace App\Filament\Resources\Equipment\Schemas;

use App\Models\Equipment;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;

class EquipmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make((fn ($record) => $record->equipment_name))
                ->schema([
                    ImageEntry::make('picture')
                        ->hiddenLabel()
                        ->disk('public')
                        ->visibility('public')
                        ->width(400)
                        ->height(160)
                ])->columnSpan(1)->compact(),

                Section::make('Equipment Details')
                ->schema([
                    TextEntry::make('equipment_name')->weight('semibold'),

                    TextEntry::make('quantity')
                        ->weight('semibold')
                        ->state(fn ($record) => 
                            $record->quantity === 0 ? 'Out of Stock':
                            $record->quantity . ' ' . ($record->quantity === 1 ? 'pc' : 'pcs')
                        ),

                    TextEntry::make('property_no')->weight('semibold'),
                    TextEntry::make('location')->weight('semibold'),
                    TextEntry::make('remarks')->weight('semibold'),
                    
                    TextEntry::make('deleted_at')
                        ->dateTime('M j, Y h:i A')
                        ->weight('semibold')
                        ->color('danger')
                        ->visible(fn (Equipment $record): bool => $record->trashed()),
                ])->columns(3)->columnSpan(2)->compact(),
            ])->columns(3);
    }
}
