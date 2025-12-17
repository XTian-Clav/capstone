<?php

namespace App\Filament\Portal\Pages;

use BackedEnum;
use Carbon\Carbon;
use App\Models\Room;
use Filament\Pages\Page;
use App\Models\ReserveRoom;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;

class Report extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationLabel = 'Reservation Reports';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentChartBar;

    protected string $view = 'filament.portal.pages.report';

    // Form fields
    public $startDate;
    public $endDate;
    public $status = null;

    // Report data
    public $reservations;
    public $statistics;

    public function mount(): void
    {
        // Default to last 30 days
        $this->startDate = now()->subDays(30)->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
        
        $this->loadData();
    }

    protected function getFormSchema(): array
    {
        return [
            DatePicker::make('startDate')
                ->label('From Date')
                ->required(),

            DatePicker::make('endDate')
                ->label('To Date')
                ->required(),

            Select::make('status')
                ->label('Status')
                ->options([
                    'Pending' => 'Pending',
                    'Approved' => 'Approved',
                    'Rejected' => 'Rejected',
                    'Completed' => 'Completed',
                ])
                ->placeholder('All Statuses')
                ->native(false),
        ];
    }

    public function loadData(): void
    {
        $query = ReserveRoom::with(['room', 'user'])
            ->whereBetween('created_at', [
                $this->startDate . ' 00:00:00',
                $this->endDate . ' 23:59:59'
            ]);

        if ($this->status) {
            $query->where('status', $this->status);
        }

        $this->reservations = $query->orderBy('created_at', 'desc')->get();

        // Calculate statistics
        $this->statistics = [
            // Status counts
            'total' => $this->reservations->count(),
            'pending' => $this->reservations->where('status', 'Pending')->count(),
            'approved' => $this->reservations->where('status', 'Approved')->count(),
            'rejected' => $this->reservations->where('status', 'Rejected')->count(),
            'completed' => $this->reservations->where('status', 'Completed')->count(),
            
            // Room stats
            'rooms_used' => $this->reservations->pluck('room_id')->unique()->count(),
            'most_booked_room' => $this->reservations->groupBy('room_id')
                ->map(fn($group) => ['room' => $group->first()->room, 'count' => $group->count()])
                ->sortByDesc('count')
                ->first(),
            
            // Customer stats
            'unique_customers' => $this->reservations->pluck('reserved_by')->unique()->count(),
            'top_customers' => $this->reservations->groupBy('reserved_by')
                ->map(fn($group) => [
                    'name' => $group->first()->reserved_by,
                    'count' => $group->count()
                ])
                ->sortByDesc('count')
                ->take(5)
                ->values(),
        ];
    }

    public function resetFilters(): void
    {
        $this->startDate = now()->subDays(30)->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
        $this->status = null;
        $this->loadData();
    }
}