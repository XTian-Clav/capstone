<?php

namespace App\Filament\Portal\Widgets;

use Carbon\Carbon;
use App\Models\ReserveRoom;
use App\Models\ReserveSupply;
use App\Models\ReserveEquipment;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ReservationsCount extends ApexChartWidget
{
    public static function canView(): bool
    {
        $user = auth()->user();
        return $user->hasAnyRole(['admin', 'super_admin']);
    }

    protected ?string $pollingInterval = '60s';
    protected static ?int $sort = 2;
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'reservationsArea';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'PITBI Reservations Comparison';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        // Month labels (Jan–Dec)
        $months = collect(range(1, 12))->map(fn($m) => Carbon::create()->month($m)->format('M'));

        // Get counts per month for each reservation type
        $roomCounts = $this->getMonthlyCounts(ReserveRoom::class);
        $equipmentCounts = $this->getMonthlyCounts(ReserveEquipment::class);
        $supplyCounts = $this->getMonthlyCounts(ReserveSupply::class);

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Rooms',
                    'data' => array_values($roomCounts),
                ],
                [
                    'name' => 'Equipment',
                    'data' => array_values($equipmentCounts),
                ],
                [
                    'name' => 'Supplies',
                    'data' => array_values($supplyCounts),
                ],
            ],
            'xaxis' => [
                'categories' => $months->toArray(),
                'labels' => [
                    'style' => ['fontFamily' => 'inherit'],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => ['fontFamily' => 'inherit'],
                ],
            ],
            'colors' => ['#2563eb', '#10b981', '#f59e0b'], // blue, green, orange
            'stroke' => [
                'curve' => 'smooth',
                'width' => 3,
            ],
            'fill' => [
                'type' => 'solid',
                'opacity' => 1,
            ],
            'dataLabels' => [
                'enabled' => false,
            ],
            'legend' => [
                'position' => 'top',
            ],
        ];
    }

    private function getMonthlyCounts(string $model): array
    {
        // Return array of 12 months with counts (fill 0 where no data)
        $raw = $model::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        return collect(range(1, 12))->map(fn($m) => $raw[$m] ?? 0)->toArray();
    }
}