<?php

namespace App\Filament\Portal\Pages;

use UnitEnum;
use Carbon\Carbon;
use Filament\Pages\Page;
use Filament\Actions\Action;
use App\Models\ReserveSupply;
use App\Models\ReserveEquipment;

class OverdueItems extends Page
{
    protected static ?int $navigationSort = 6;
    protected static string|UnitEnum|null $navigationGroup = 'Generate Reports';

    protected string $view = 'filament.portal.pages.overdue-items';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('super_admin');
    }

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('super_admin');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print_overdue_report')
                ->label('Print Overdue Items')
                ->icon('heroicon-s-printer')
                ->color('primary')
                ->url(route('OverdueItems', request()->only(['month', 'year'])))
                ->openUrlInNewTab(),
        ];
    }

    public $borrowedEquipment = [];
    public $borrowedSupply = [];
    public $overdueEquipment = [];
    public $overdueSupply = [];

    public function mount(): void
    {
        $startOfToday = Carbon::today(); 

        $allEquipment = ReserveEquipment::with('equipment')
            ->where('status', 'Approved')
            ->get();

        $allSupplies = ReserveSupply::with('supply')
            ->where('status', 'Approved')
            ->get();

        $this->borrowedEquipment = $allEquipment->where('end_date', '>=', $startOfToday)
            ->sortByDesc('end_date');

        $this->borrowedSupply = $allSupplies->where('end_date', '>=', $startOfToday)
            ->sortByDesc('end_date');

        $this->overdueEquipment = $allEquipment->where('end_date', '<', $startOfToday)
            ->sortByDesc('end_date');

        $this->overdueSupply = $allSupplies->where('end_date', '<', $startOfToday)
            ->sortByDesc('end_date');
    }
}

