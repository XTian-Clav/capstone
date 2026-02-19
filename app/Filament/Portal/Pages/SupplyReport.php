<?php

namespace App\Filament\Portal\Pages;

use UnitEnum;
use App\Models\Supply;
use Filament\Pages\Page;
use Filament\Actions\Action;
use App\Models\ReserveSupply;

class SupplyReport extends Page
{
    protected static ?int $navigationSort = 3;
    protected static string|UnitEnum|null $navigationGroup = 'Generate Reports';

    protected string $view = 'filament.portal.pages.supply-report';

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
                ->url(route('SupplyReport', request()->only(['month', 'year'])))
                ->openUrlInNewTab(),
        ];
    }

    public $supplies;
    public $totalSupply = 0;
    public $totalAvailable = 0;
    public $totalReserved = 0;
    public $totalUnavailable = 0;

    public $totalPending = 0;
    public $totalApproved = 0;
    public $totalRejected = 0;
    public $totalCompleted = 0;

    public $mostBorrowed;
    public $criticalStock;
    public $lowStock;
    public $outOfStock;

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

        $this->availableYears = ReserveSupply::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray() ?: [now()->year];

        $this->supplies = Supply::withSum('unavailable as total_unavailable_qty', 'unavailable_quantity')
            ->withSum(['reservations as current_reserved_qty' => fn ($q) => $q->where('status', 'Approved')], 'quantity')
            ->withCount([
                'reservations as pending_count' => fn ($q) => $q->where('status', 'Pending')
                    ->whereYear('created_at', $year)
                    ->when($month, fn($query) => $query->whereMonth('created_at', $month)),
                
                'reservations as approved_count' => fn ($q) => $q->where('status', 'Approved')
                    ->whereYear('created_at', $year)
                    ->when($month, fn($query) => $query->whereMonth('created_at', $month)),

                'reservations as rejected_count' => fn ($q) => $q->where('status', 'Rejected')
                    ->whereYear('created_at', $year)
                    ->when($month, fn($query) => $query->whereMonth('created_at', $month)),

                'reservations as completed_count' => fn ($q) => $q->where('status', 'Completed')
                    ->whereYear('created_at', $year)
                    ->when($month, fn($query) => $query->whereMonth('created_at', $month)),
            ])
            ->orderBy('item_name', 'asc')
            ->get();

        foreach ($this->supplies as $supply) {
            $supply->borrow_count = $supply->approved_count + $supply->completed_count;
            
            $supply->available = $supply->quantity 
                - ($supply->current_reserved_qty ?? 0) 
                - ($supply->total_unavailable_qty ?? 0);
            
            $supply->availability_percentage = $supply->quantity > 0 
                ? ($supply->available / $supply->quantity) * 100 
                : 0;

            $this->totalPending += $supply->pending_count;
            $this->totalApproved += $supply->approved_count;
            $this->totalRejected += $supply->rejected_count;
            $this->totalCompleted += $supply->completed_count;
            
            $this->totalSupply += $supply->quantity;
            $this->totalReserved += ($supply->current_reserved_qty ?? 0);
            $this->totalUnavailable += ($supply->total_unavailable_qty ?? 0);
        }

        $this->totalAvailable = $this->totalSupply - $this->totalReserved - $this->totalUnavailable;
        
        $this->mostBorrowed = $this->supplies->sortByDesc('borrow_count')->first();
        $this->lowStock = $this->supplies->filter(fn ($s) => $s->availability_percentage > 26 && $s->availability_percentage <= 50);
        $this->criticalStock = $this->supplies->filter(fn ($s) => $s->available > 0 && $s->availability_percentage <= 25);
        $this->outOfStock = $this->supplies->filter(fn ($s) => $s->available <= 0);
    }
}