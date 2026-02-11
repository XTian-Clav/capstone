<?php

namespace App\Filament\Portal\Pages;

use UnitEnum;
use Carbon\Carbon;
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
                ->label('Print Returned Items')
                ->icon('heroicon-s-printer')
                ->color('info')
                ->url(route('ReturnedItems', request()->only(['month', 'year'])))
                ->openUrlInNewTab(),
        ];
    }

    public $returnedEquipment = [];
    public $returnedSupply = [];
    
    public $borrowedEquipment = [];
    public $borrowedSupply = [];

    public $overdueEquipment = [];
    public $overdueSupply = [];
    
    public $availableYears = [];

    public function mount(): void
    {
        if (!request()->query('month') && !request()->query('year')) {
            $this->redirect(request()->fullUrlWithQuery([
                'month' => now()->month,
                'year' => now()->year,
            ]));
            return;
        }
    
        $month = request('month');
        $year = request('year', now()->year);
        $now = Carbon::now();
    
        $this->availableYears = ReserveEquipment::selectRaw('YEAR(created_at) as year')
            ->union(ReserveSupply::selectRaw('YEAR(created_at) as year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray() ?: [now()->year];
    
        $allEquipment = ReserveEquipment::with('equipment')
            ->whereIn('status', ['Completed', 'Approved'])
            ->get();
    
        $this->returnedEquipment = $allEquipment->where('status', 'Completed')
            ->filter(function ($item) use ($month, $year) {
                $date = Carbon::parse($item->updated_at);
                return $date->year == $year && (!$month || $date->month == $month);
            })->sortByDesc('updated_at');
    
        $this->borrowedEquipment = $allEquipment->where('status', 'Approved')
            ->where('end_date', '>=', $now)
            ->sortByDesc('end_date');
    
        $this->overdueEquipment = $allEquipment->where('status', 'Approved')
            ->where('end_date', '<', $now)
            ->sortByDesc('end_date');
    
        $allSupplies = ReserveSupply::with('supply')
            ->whereIn('status', ['Completed', 'Approved'])
            ->get();
    
        $this->returnedSupply = $allSupplies->where('status', 'Completed')
            ->filter(function ($item) use ($month, $year) {
                $date = Carbon::parse($item->updated_at);
                return $date->year == $year && (!$month || $date->month == $month);
            })->sortByDesc('updated_at');
    
        $this->borrowedSupply = $allSupplies->where('status', 'Approved')
            ->where('end_date', '>=', $now)
            ->sortByDesc('end_date');
    
        $this->overdueSupply = $allSupplies->where('status', 'Approved')
            ->where('end_date', '<', $now)
            ->sortByDesc('end_date');
    }
}
