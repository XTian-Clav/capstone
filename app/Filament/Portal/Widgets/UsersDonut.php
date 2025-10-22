<?php

namespace App\Filament\Portal\Widgets;

use App\Models\User;
use App\Models\Mentor;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class UsersDonut extends ApexChartWidget
{
    protected ?string $pollingInterval = '60s';
    protected static ?int $sort = 6;
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'usersDonut';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'PITBI Members';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        // Get counts
        $incubatees = User::whereHas('roles', fn ($q) => $q->where('name', 'incubatee'))->count();
        $investors = User::whereHas('roles', fn ($q) => $q->where('name', 'investor'))->count();
        $mentors = Mentor::count();

        return [
            'chart' => [
                'type' => 'donut',
                'height' => 300,
            ],
            'series' => [$incubatees, $investors, $mentors],
            'labels' => ['Incubatees', 'Investors', 'Mentors'],
            'legend' => [
                'position' => 'bottom',
                'labels' => [
                    'colors' => '#374151',
                ],
            ],
            'dataLabels' => [
                'enabled' => true,
            ],
        ];
    }
}
