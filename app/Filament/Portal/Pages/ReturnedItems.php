<?php

namespace App\Filament\Portal\Pages;

use UnitEnum;
use Filament\Pages\Page;
use Filament\Actions\Action;
use App\Models\ReserveSupply;
use App\Models\ReserveEquipment;

class ReturnedItems extends Page
{
    protected static ?int $navigationSort = 5;
    protected static string|UnitEnum|null $navigationGroup = 'Generate Reports';

    protected string $view = 'filament.portal.pages.returned-items';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('super_admin');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print_report')
                ->label('Print')
                ->icon('heroicon-s-printer')
                ->color('info')
                ->url(route('ReturnedItems'))
                ->openUrlInNewTab(),
        ];
    }

    public $returnedEquipment = [];
    public $returnedSupply = [];
    
    public function mount(): void
    {
        $this->returnedEquipment = ReserveEquipment::with('equipment')
            ->where('status', 'Completed')
            ->latest('updated_at')
            ->get();

        $this->returnedSupply = ReserveSupply::with('supply')
            ->where('status', 'Completed')
            ->latest('updated_at')
            ->get();
    }
}
