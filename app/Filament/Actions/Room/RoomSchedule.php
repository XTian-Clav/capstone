<?php

namespace App\Filament\Actions\Room;

use App\Models\Room;
use Filament\Actions\Action;
use Filament\Support\Enums\Size;
use Filament\Forms\Components\Radio;
use Filament\Schemas\Components\Section;
use App\Filament\Portal\Resources\Rooms\RoomResource;

class RoomSchedule
{
    public static function make(): Action
    {
        return Action::make('view_schedules')
            ->button()
            ->outlined()
            ->color('success')
            ->size(Size::Small)
            ->label('View Schedules')
            ->icon('heroicon-o-calendar-days')
            ->requiresConfirmation()
            ->modalHeading('Select Room')
            ->modalIcon('heroicon-o-building-office')
            ->modalDescription('Please select a room to view their schedules.')
            ->modalSubmitActionLabel('Confirm')
            ->schema([
                Section::make([
                    Radio::make('room_id')
                        ->label('Select Room')
                        ->options(Room::pluck('room_type', 'id'))
                        ->required(),
                ])
            ])
            ->action(fn (array $data) => redirect(
                RoomResource::getUrl('view', ['record' => $data['room_id']])
            ));
    }
}
