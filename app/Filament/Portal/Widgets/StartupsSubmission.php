<?php

namespace App\Filament\Portal\Widgets;

use Carbon\Carbon;
use App\Models\Startup;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class StartupsSubmission extends ApexChartWidget
{
    public static function canView(): bool
    {
        $user = auth()->user();
        return $user->hasAnyRole(['admin', 'super_admin']);
    }

    protected ?string $pollingInterval = '60s';
    protected static ?int $sort = 5;
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'startupsColumn';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Startups Submissions per Month';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        // Query count of reservations per month (for current year)
        $data = Startup::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        // Build full months array (to fill missing months with zero)
        $months = collect(range(1, 12))->map(fn ($m) => Carbon::create()->month($m)->format('M'));
        $counts = collect(range(1, 12))->map(fn ($m) => $data[$m] ?? 0);

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Number of Startups',
                    'data' => $counts->toArray(),
                ],
            ],
            'xaxis' => [
                'categories' =>  $months->toArray(),
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'colors' => ['#f59e0b'],
        ];
    }
}
