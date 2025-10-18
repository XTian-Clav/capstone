<?php

namespace App\Providers;

use Spatie\Health\Facades\Health;
use Illuminate\Support\ServiceProvider;
use BezhanSalleh\FilamentShield\Commands;
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
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //FilamentShield::prohibitDestructiveCommands($this->app->isProduction());

        //Spatie Health Check
        Health::checks([
            OptimizedAppCheck::new(),
            DebugModeCheck::new(),
            EnvironmentCheck::new(),
            DatabaseCheck::new(),
            CacheCheck::new(),
            QueueCheck::new(),
            ScheduleCheck::new(),
            UsedDiskSpaceCheck::new()->warnWhenUsedSpaceIsAbovePercentage(80),
        ]);
    }
}
