<?php

namespace App\Filament\Actions;

use Filament\Actions\Action;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Components\Section;
use App\Filament\Portal\Resources\Rooms\RoomResource;

class EquipmentTerms
{
    public static function make(): Action
    {
        return Action::make('terms')
            ->link()
            ->color('secondary')
            ->icon('heroicon-s-question-mark-circle')
            ->label('Read Terms and Conditions')
            ->modalHeading('Terms and Conditions')
            ->modalSubmitAction(false) 
            ->modalCancelActionLabel('Close')
            ->schema([
                Section::make('Equipment/Supplies Reservation Guidelines')
                ->schema([
                    Text::make('● I agree to promptly return the equipment/items borrowed.'),
                    Text::make('● I agree to pay for any damage or loss of the equipment/items during the time when the equipment is in my possession.'),
                    Text::make('● I pledge that the equipment/items I borrowed from PITBI/PSU will be used solely for the official purpose stated above and not for personal purposes.'),
                ])->compact()
            ]);
    }
}
