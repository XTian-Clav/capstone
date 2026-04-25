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

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('super_admin');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print_report')
                ->label('Print Returned Items')
                ->icon('heroicon-s-printer')
                ->color('primary')
                ->url(route('ReturnedItems', request()->only(['month', 'year'])))
                ->openUrlInNewTab(),
        ];
    }

    public $returnedEquipment = [];
    public $returnedSupply = [];
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

        $this->availableYears = ReserveEquipment::selectRaw('YEAR(created_at) as year')
            ->union(ReserveSupply::selectRaw('YEAR(created_at) as year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray() ?: [now()->year];

        // Equipment query with conditional month filter
        $this->returnedEquipment = ReserveEquipment::with('equipment')
            ->where('status', 'Completed')
            ->whereYear('updated_at', $year)
            ->when($month, function ($query) use ($month) {
                $query->whereMonth('updated_at', $month);
            })
            ->orderBy('updated_at')
            ->get();

        // Supply query with conditional month filter
        $this->returnedSupply = ReserveSupply::with('supply')
            ->where('status', 'Completed')
            ->whereYear('updated_at', $year)
            ->when($month, function ($query) use ($month) {
                $query->whereMonth('updated_at', $month);
            })
            ->orderBy('updated_at')
            ->get();
    }
}