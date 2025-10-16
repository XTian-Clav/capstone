<?php

namespace App\Filament\Portal\Resources\ReserveSupplies\Schemas;

use App\Models\Supply;
use App\Models\ReserveSupply;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Illuminate\Support\Facades\Storage;

class ReserveSupplyInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(fn ($record) => $record->supply?->item_name ?? 'Supply')
                ->schema([
                    ImageEntry::make('picture')
                        ->getStateUsing(fn ($record) => $record->supply?->picture) // just the stored path
                        ->disk('public')
                        ->hiddenLabel()
                        ->width(400)
                        ->height(160)
                        ->columnSpanFull(),

                    Section::make()
                    ->schema([
                        TextEntry::make('quantity')
                            ->weight('semibold')
                            ->badge()
                            ->color('info')
                            ->formatStateUsing(fn ($state) => $state . ' ' . ($state == 1 ? 'pc' : 'pcs')),
                        
                        TextEntry::make('status')
                            ->weight('semibold')
                            ->badge()
                            ->colors([
                                'warning' => 'Pending',
                                'success' => 'Approved',
                                'danger' => 'Rejected',
                            ]),
                    ])->columnSpanFull()->columns(2)->compact(),
                ])->columnSpan(1)->columns(2)->compact(),
                
                    Section::make('Reservation Details')
                    ->schema([
                        Section::make()
                        ->schema([
                            TextEntry::make('reserved_by')->weight('semibold'),
                            TextEntry::make('email')->weight('semibold')->columnSpan(2),
                            TextEntry::make('contact')->weight('semibold'),
                            TextEntry::make('office')->weight('semibold')->columnSpan(2),
                        ])->columnSpanFull()->columns(3)->compact(),
                    
                        Section::make()
                        ->schema([
                            TextEntry::make('start_date')
                                ->dateTime('M j, Y h:i A')
                                ->weight('semibold'),
                            
                            TextEntry::make('end_date') 
                                ->dateTime('M j, Y h:i A')
                                ->weight('semibold'),
                            
                            TextEntry::make('created_at')
                                ->dateTime('M j, Y h:i A')
                                ->label('Submittion Date')
                                ->weight('semibold'),
                            
                            TextEntry::make('deleted_at')
                                ->dateTime('M j, Y h:i A')
                                ->weight('semibold')
                                ->color('danger')
                                ->visible(fn (ReserveSupply $record): bool => $record->trashed()),
                        ])->columnSpanFull()->columns(2)->compact(),
                ])->columns(3)->columnSpan(2)->compact(),
            ])->columns(3);
    }
}
