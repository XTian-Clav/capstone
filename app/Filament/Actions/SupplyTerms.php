<?php

namespace App\Filament\Actions;

use Filament\Actions\Action;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Components\Section;

class SupplyTerms
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
                Section::make('Supplies Reservation Guidelines')
                ->schema([
                    Text::make('● I agree to replace the exact quantity of the supplies used.'),
                    Text::make('● I assume full responsibility for their proper handling and condition. PITBI/PSU is not obligated to provide replacements for any supplies damaged while in my possession.'),
                    Text::make('● I pledge that the supplies borrowed from PITBI/PSU will be used solely for the official purpose stated above and not for personal purposes.'),
                ])->compact()
            ]);
    }
}
