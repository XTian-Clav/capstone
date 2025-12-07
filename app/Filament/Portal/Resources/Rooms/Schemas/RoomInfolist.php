<?php

namespace App\Filament\Portal\Resources\Rooms\Schemas;

use Carbon\Carbon;
use App\Models\Room;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;

class RoomInfolist
{
    public static function configure(Schema $schema): Schema
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth()->endOfDay(); 

        $approvedReservations = function ($record) use ($startOfMonth, $endOfMonth) {
            return $record->reservations()
                ->where('status', 'Approved')
                ->where('room_id', $record->id)
                ->where('start_date', '<=', $endOfMonth) 
                ->where('end_date', '>=', $startOfMonth)
                //->whereBetween('start_date', [$startOfMonth, $endOfMonth]) //Old month validation
                ->with('user')
                ->orderBy('start_date')
                ->get();
        };

        return $schema
            ->components([
                Section::make((fn ($record) => $record->room_type))
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
                    Section::make()
                    ->schema([
                        TextEntry::make('inclusions'),
                    ])->compact()->secondary(),
                ])->columnSpan(1)->compact(),

                Section::make('Room Details')
                ->schema([
                    TextEntry::make('room_type')->weight('semibold')->label('Room Type:')->inlineLabel(),
                    TextEntry::make('capacity')->weight('semibold')->label('Capacity:')->inlineLabel(),
                    TextEntry::make('location')->weight('semibold')->label('Location:')->inlineLabel(),

                    TextEntry::make('room_rate')
                        ->numeric()
                        ->numeric()
                        ->state(fn ($record) =>
                            ($record->room_rate && $record->room_rate > 0)
                                ? '₱' . number_format($record->room_rate)
                                : 'None'
                        )
                        ->weight('semibold')
                        ->label('Room Details:')
                        ->inlineLabel(),
                    
                    TextEntry::make('is_available')
                        ->label('Availability:')
                        ->state(fn ($record) => $record->is_available ? 'Room Available' : 'Room Unavailable')
                        ->color(fn ($record) => $record->is_available ? 'success' : 'danger')
                        ->weight('semibold')
                        ->inlineLabel(),
                    
                    TextEntry::make('deleted_at')
                        ->dateTime('M j, Y h:i A')
                        ->weight('semibold')
                        ->color('danger')
                        ->label('Deleted At:')
                        ->inlineLabel()
                        ->visible(fn (Room $record): bool => $record->trashed()),
                ])->columnSpan(2)->compact(),

                Section::make(fn ($record) => 'Approved Room Schedules for ' . $record->room_type . ' (' . Carbon::now()->format('F Y') . ')')
                ->schema([
                    // 1. Reserved By Column
                    TextEntry::make('reserved_by_list')
                        ->label('Reserved By')
                        ->placeholder('No approved reservations found.')
                        ->getStateUsing(fn ($record) => $approvedReservations($record)->pluck('user.name'))
                        ->listWithLineBreaks(),

                    // 2. Company/Organization
                    TextEntry::make('company_list')
                        ->label('Company/Organization')
                        ->placeholder('—')
                        ->getStateUsing(fn ($record) => $approvedReservations($record)->pluck('company'))
                        ->listWithLineBreaks()
                        ->columnSpan(1),

                    // 3. Start Time
                    TextEntry::make('start_time_list')
                        ->label('Start Time')
                        ->placeholder('—')
                        ->hiddenLabel(false)
                        ->getStateUsing(fn ($record) => $approvedReservations($record)->pluck('start_date'))
                        ->dateTime('M j, Y h:i A')
                        ->listWithLineBreaks(),
                    
                    // 4. End Time
                    TextEntry::make('end_time_list')
                        ->label('End Time')
                        ->placeholder('—')
                        ->getStateUsing(fn ($record) => $approvedReservations($record)->pluck('end_date'))
                        ->dateTime('M j, Y h:i A')
                        ->listWithLineBreaks()
                        ->columnSpan(1),
                ])->columns(4)->columnSpanFull(),
            ])->columns(3);
    }
}
