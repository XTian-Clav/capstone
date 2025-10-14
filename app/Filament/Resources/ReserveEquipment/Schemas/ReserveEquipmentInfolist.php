<?php

namespace App\Filament\Resources\ReserveEquipment\Schemas;

use App\Models\Equipment;
use App\Models\ReserveEquipment;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Illuminate\Support\Facades\Storage;

class ReserveEquipmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(fn ($record) => $record->equipment?->equipment_name ?? 'Equipment')
                ->schema([
                    ImageEntry::make('picture')
                        ->getStateUsing(fn ($record) => $record->equipment?->picture) // just the stored path
                        ->disk('public')
                        ->hiddenLabel()
                        ->width(400)
                        ->height(160),
                ])->columnSpan(1)->compact(),
                
                Section::make('Equipment Reservation Details')
                ->schema([
                    TextEntry::make('reserved_by')->weight('semibold'),
                    TextEntry::make('status')->weight('semibold'),
                    TextEntry::make('quantity')
                        ->weight('semibold')
                        ->formatStateUsing(fn ($state) => $state . ' ' . ($state == 1 ? 'pc' : 'pcs')),
                    
                    TextEntry::make('created_at')
                        ->dateTime('M j, Y h:i A')
                        ->label('Submitted at')
                        ->weight('semibold'),
                    
                    TextEntry::make('start_date')
                        ->dateTime('M j, Y h:i A')
                        ->weight('semibold'),
                    
                    TextEntry::make('end_date') 
                        ->dateTime('M j, Y h:i A')
                        ->weight('semibold'),
                    
                    TextEntry::make('deleted_at')
                        ->dateTime('M j, Y h:i A')
                        ->weight('semibold')
                        ->color('danger')
                        ->visible(fn (ReserveEquipment $record): bool => $record->trashed()),
                ])->columns(3)->columnSpan(2)->compact(),
            ])->columns(3);
    }
}
