<?php

namespace App\Filament\Portal\Resources\UnavailableEquipment\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;

class UnavailableEquipmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(fn ($record) => $record->equipment?->equipment_name ?? 'Equipment')
                ->schema([
                    ImageEntry::make('picture')
                        ->hiddenLabel()
                        ->disk('public')
                        ->visibility('public')
                        ->width(400)
                        ->height(160)
                        ->alignLeft()
                        ->defaultImageUrl(url('storage/default/no-image.png'))
                        ->extraImgAttributes([
                            'alt' => 'Logo',
                            'loading' => 'lazy',
                            'class' => 'rounded-xl object-cover',
                        ]),
                ])->columnSpan(1)->compact(),

                Section::make('Equipment Details')
                ->schema([
                    TextEntry::make('status')->badge()->color('danger')->label('Status:')->inlineLabel(),
                    TextEntry::make('unavailable_quantity')
                        ->numeric()
                        ->inlineLabel()
                        ->weight('semibold')
                        ->label('Quantity:')
                        ->formatStateUsing(fn ($state) => $state . ' ' . ($state == 1 ? 'pc' : 'pcs')),

                    TextEntry::make('created_at')
                        ->dateTime('M j, Y h:i A')
                        ->label('Created At:')
                        ->weight('semibold')
                        ->inlineLabel(),
                        
                    TextEntry::make('remarks')->weight('semibold')->label('Remarks:')->inlineLabel(),
                ])->columnSpan(2)->compact(),
            ])->columns(3);
    }
}
