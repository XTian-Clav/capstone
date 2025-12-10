<?php

namespace App\Filament\Portal\Resources\ReserveSupplies\Schemas;

use Filament\Schemas\Schema;
use App\Models\ReserveSupply;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;

class ReserveSupplyInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(fn ($record) => $record->supply?->item_name ?? 'Supply')
                ->schema([
                    ImageEntry::make('picture')
                        ->getStateUsing(fn ($record) => $record->supply?->picture)
                        ->disk('public')
                        ->hiddenLabel()
                        ->width(400)
                        ->height(160)
                        ->columnSpanFull()
                        ->alignCenter()
                        ->defaultImageUrl(url('storage/default/no-image.png'))
                        ->extraImgAttributes([
                            'alt' => 'Logo',
                            'loading' => 'lazy',
                            'class' => 'rounded-xl object-cover',
                        ]),

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
                                'cyan' => 'Completed',
                            ]),
                    ])->columnSpanFull()->columns(2)->compact(),
                    Section::make('Admin Comment')
                    ->schema([
                        TextEntry::make('admin_comment')->hiddenLabel()->color('danger'),
                    ])->columnSpanFull()->compact()->visible(fn (ReserveSupply $record) => $record->status === 'Rejected'),
                ])->columnSpan(1)->columns(2)->compact()->secondary(),
                
                    Section::make('Reservation Details')
                    ->schema([
                        Section::make()
                        ->schema([
                            TextEntry::make('reserved_by')->weight('semibold')->label('Reserved By:')->inlineLabel(),
                            TextEntry::make('email')->weight('semibold')->label('Email:')->inlineLabel(),
                            TextEntry::make('contact')->weight('semibold')->label('Contact:')->inlineLabel(),
                            TextEntry::make('company')->weight('semibold')->label('Company:')->inlineLabel(),
                        ])->columnSpanFull()->compact(),
                    
                        Section::make()
                        ->schema([
                            TextEntry::make('start_date')
                            ->dateTime('M j, Y h:i A')
                            ->label('Start Date:')
                            ->weight('semibold')
                            ->inlineLabel(),
                        
                            TextEntry::make('end_date') 
                                ->dateTime('M j, Y h:i A')
                                ->label('End Date:')
                                ->weight('semibold')
                                ->inlineLabel(),
                            
                            TextEntry::make('created_at')
                                ->dateTime('M j, Y h:i A')
                                ->label('Submission Date:')
                                ->weight('semibold')
                                ->inlineLabel(),
                        ])->columnSpanFull()->compact(),
                ])->columns(3)->columnSpan(2)->compact()->secondary(),
            ])->columns(3);
    }
}
