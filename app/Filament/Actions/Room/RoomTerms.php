<?php

namespace App\Filament\Actions\Room;

use Filament\Actions\Action;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Components\Section;
use App\Filament\Portal\Resources\Rooms\RoomResource;

class RoomTerms
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
                Section::make('Room Reservation Guidelines')
                ->schema([
                    Text::make('● Adhere to the official operating hours of PITBI, ensuring alignment with designated working hours.'),
                    Text::make('● Turn off all electronic devices, lights, and aircons, before leaving the office to conserve energy.'),
                    Text::make('● Leave the office space in the same or better condition than found, disposing of trash properly.'),
                    Text::make('● Be considerate of other PITBI occupants, avoiding personal items in common areas and tidying up after using communal spaces.'),
                    Text::make('● Contribute to a responsible and respectful office-sharing culture at PITBI by following these guidelines, fostering a productive and sustainable workspace.'),
                    Text::make('● Securely lock doors and windows when leaving the office and promptly report any security concerns to the PITBI staff.'),
                    Text::make('● Accomplish a Client Satisfaction Measurement after the event.'),
                ])->compact(),
            ]);
    }
}
