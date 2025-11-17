<?php

namespace App\Filament\Portal\Resources\Equipment\Schemas;

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
                        ->alignCenter()
                        ->defaultImageUrl(url('storage/default/no-image.png'))
                        ->extraImgAttributes([
                            'alt' => 'Logo',
                            'loading' => 'lazy',
                            'class' => 'rounded-xl object-cover',
                        ]),
                ])->columnSpan(1)->compact(),

                Section::make('Equipment Details')
                ->schema([
                    TextEntry::make('equipment_name')->weight('semibold')->label('Equipment Name:')->inlineLabel(),

                    TextEntry::make('quantity')
                        ->weight('semibold')
                        ->state(fn ($record) => 
                            $record->quantity === 0 ? 'Out of Stock':
                            $record->quantity . ' ' . ($record->quantity === 1 ? 'pc' : 'pcs')
                        )
                        ->inlineLabel()
                        ->label('Quantity:'),

                    TextEntry::make('property_no')->weight('semibold')->label('Property No:')->inlineLabel(),
                    TextEntry::make('location')->weight('semibold')->label('Location:')->inlineLabel(),
                    TextEntry::make('remarks')->weight('semibold')->label('Remarks:')->inlineLabel(),
                    
                    TextEntry::make('deleted_at')
                        ->dateTime('M j, Y h:i A')
                        ->weight('semibold')
                        ->color('danger')
                        ->label('Deleted At:')
                        ->inlineLabel()
                        ->visible(fn (Equipment $record): bool => $record->trashed()),
                ])->columnSpan(2)->compact(),
            ])->columns(3);
    }
}
