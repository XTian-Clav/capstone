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

    public $search = '';

    public function getViewData(): array
    {
        $startOfToday = Carbon::today();

        $allEquipment = ReserveEquipment::with('equipment')
            ->where('status', 'Approved')
            ->get();

        $allSupplies = ReserveSupply::with('supply')
            ->where('status', 'Approved')
            ->get();

        $borrowedEquipment = $allEquipment->where('end_date', '>=', $startOfToday)
            ->sortByDesc('end_date');

        $borrowedSupply = $allSupplies->where('end_date', '>=', $startOfToday)
            ->sortByDesc('end_date');

        $overdueEquipment = $allEquipment->where('end_date', '<', $startOfToday)
            ->sortByDesc('end_date');

        $overdueSupply = $allSupplies->where('end_date', '<', $startOfToday)
            ->sortByDesc('end_date');

        if ($this->search) {
            $search = strtolower($this->search);

            $borrowedEquipment = $borrowedEquipment->filter(fn ($i) =>
                str_contains(strtolower($i->reserved_by), $search) ||
                str_contains(strtolower($i->equipment?->equipment_name), $search)
            );

            $borrowedSupply = $borrowedSupply->filter(fn ($i) =>
                str_contains(strtolower($i->reserved_by), $search) ||
                str_contains(strtolower($i->supply?->item_name), $search)
            );

            $overdueEquipment = $overdueEquipment->filter(fn ($i) =>
                str_contains(strtolower($i->reserved_by), $search) ||
                str_contains(strtolower($i->equipment?->equipment_name), $search)
            );

            $overdueSupply = $overdueSupply->filter(fn ($i) =>
                str_contains(strtolower($i->reserved_by), $search) ||
                str_contains(strtolower($i->supply?->item_name), $search)
            );
        }

        return [
            'borrowedEquipment' => $borrowedEquipment,
            'borrowedSupply' => $borrowedSupply,
            'overdueEquipment' => $overdueEquipment,
            'overdueSupply' => $overdueSupply,
        ];
    }
}