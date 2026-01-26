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
                ->url(route('ReturnedItems'))
                ->openUrlInNewTab(),
        ];
    }

    public $returnedEquipment = [];
    public $returnedSupply = [];
    
    public $borrowedEquipment = [];
    public $borrowedSupply = [];

    public $overdueEquipment = [];
    public $overdueSupply = [];
    
    public function mount(): void
    {
        $now = Carbon::now();

        $allEquipment = ReserveEquipment::with('equipment')
            ->whereIn('status', ['Completed', 'Approved'])
            ->get();

        $this->returnedEquipment = $allEquipment->where('status', 'Completed')->sortByDesc('updated_at');
        
        $approvedEquip = $allEquipment->where('status', 'Approved');
        $this->borrowedEquipment = $approvedEquip->where('end_date', '>=', $now)->sortByDesc('end_date');
        $this->overdueEquipment = $approvedEquip->where('end_date', '<', $now)->sortByDesc('end_date');

        $allSupplies = ReserveSupply::with('supply')
            ->whereIn('status', ['Completed', 'Approved'])
            ->get();

        $this->returnedSupply = $allSupplies->where('status', 'Completed')->sortByDesc('updated_at');

        $approvedSupp = $allSupplies->where('status', 'Approved');
        $this->borrowedSupply = $approvedSupp->where('end_date', '>=', $now)->sortByDesc('end_date');
        $this->overdueSupply = $approvedSupp->where('end_date', '<', $now)->sortByDesc('end_date');
    }
}
