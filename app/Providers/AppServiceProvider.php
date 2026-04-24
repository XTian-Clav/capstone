<?php

namespace App\Providers;

use Filament\Tables\Table;
use Filament\Support\Enums\Width;
use Spatie\Health\Facades\Health;
use Filament\Support\Colors\Color;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Filament\Tables\Enums\FiltersLayout;
use BezhanSalleh\FilamentShield\Commands;
use Filament\Tables\Enums\PaginationMode;
use Filament\Support\Facades\FilamentColor;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\QueueCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\ScheduleCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Checks\EnvironmentCheck;
use Spatie\Health\Checks\Checks\OptimizedAppCheck;
use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use BezhanSalleh\FilamentShield\Facades\FilamentShield;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        FilamentColor::register([
            'danger' => Color::Red,
            'gray' => Color::Zinc,
            'info' => Color::Cyan,
            'primary' => Color::Orange,
            'success' => Color::Emerald,
            'warning' => Color::Yellow,
        ]);
        
        /* Hosting with no SSH access
        $this->app->bind('path.public', function () {
            return base_path() . '/public_html';
        });
        
        app()->usePublicPath(base_path() . '/public_html');
        */
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //FilamentShield::prohibitDestructiveCommands($this->app->isProduction());

        Paginator::useBootstrapfive();

        //Global Table Settings
        Table::configureUsing(function (Table $table): void {
            $table
                ->filtersFormColumns(2)
                ->filtersFormWidth(Width::ExtraLarge)
                ->paginated([10, 25, 50, 100, 'all'])
                ->paginationMode(PaginationMode::Cursor);
        });
    }
}
