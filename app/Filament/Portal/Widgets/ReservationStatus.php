<?php

namespace App\Filament\Portal\Widgets;

use App\Models\ReserveRoom;
use App\Models\ReserveEquipment;
use App\Models\ReserveSupply;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ReservationStatus extends ApexChartWidget
{
    public static function canView(): bool
    {
        $user = auth()->user();
        return $user->hasAnyRole(['admin', 'super_admin']);
    }

    protected ?string $pollingInterval = '60s';
    protected static ?int $sort = 3;
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'reservationStatus';
    
    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Total Reservation Status Summary';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $statuses = ['pending', 'approved', 'rejected'];

        $roomCounts = $this->getStatusCounts(ReserveRoom::class, $statuses);
        $equipmentCounts = $this->getStatusCounts(ReserveEquipment::class, $statuses);
        $supplyCounts = $this->getStatusCounts(ReserveSupply::class, $statuses);

        // Combine totals per status
        $totals = collect($statuses)->map(function ($status) use ($roomCounts, $equipmentCounts, $supplyCounts) {
            return ($roomCounts[$status] ?? 0)
                + ($equipmentCounts[$status] ?? 0)
                + ($supplyCounts[$status] ?? 0);
        })->toArray();

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Total Reservations',
                    'data' => array_values($totals),
                ],
            ],
            'xaxis' => [
                'categories' => ['Pending', 'Approved', 'Rejected'],
                'labels' => ['style' => ['fontFamily' => 'inherit']],
            ],
            'colors' => ['#facc15', '#22c55e', '#ef4444'], // yellow, green, red
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 4,
                    'horizontal' => true,
                    'distributed' => true, // gives each bar its own color
                ],
            ],
            'dataLabels' => ['enabled' => true],
            'legend' => ['show' => false],
        ];
    }

    private function getStatusCounts(string $model, array $statuses): array
    {
        return $model::select('status', DB::raw('COUNT(*) as count'))
            ->whereIn('status', $statuses)
            ->groupBy('status')
            ->pluck('count', 'status')
            ->mapWithKeys(fn($v, $k) => [strtolower($k) => $v])
            ->toArray();
    }
}
