<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Enums\ThemeMode;
use Filament\Pages\Dashboard;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Navigation\NavigationGroup;
use Filament\Widgets\FilamentInfoWidget;
use App\Filament\Pages\HealthCheckResults;
use Filament\Http\Middleware\Authenticate;
use App\Filament\Portal\Pages\Auth\EditProfile;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Muazzam\SlickScrollbar\SlickScrollbarPlugin;
use Filament\Http\Middleware\AuthenticateSession;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use ShuvroRoy\FilamentSpatieLaravelHealth\FilamentSpatieLaravelHealthPlugin;

class PortalPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('portal')
            ->path('portal')
            
            ->brandName('PITBI Portal')
            ->topbar(false)
            ->globalSearch(false)
            ->breadcrumbs(false)
            
            ->login()
            ->registration()
            ->passwordReset()
            ->emailVerification()
            ->emailChangeVerification()
            ->authGuard('web')

            ->profile(EditProfile::class)

            ->font('Poppins')
            ->defaultThemeMode(ThemeMode::Light)
            ->brandLogoHeight('2rem')
            ->brandLogo(asset('assets/logo/light-theme-alt.png'))
            ->darkModeBrandLogo(asset('assets/logo/dark-theme.png'))
            ->favicon(asset('assets/favicon/favicon.ico'))

            ->colors([
                'primary' => Color::Amber,
                'secondary' => Color::Gray,
                'success' => Color::Emerald,
                'danger' => Color::Red,
                'warning' => Color::Yellow,
                'info' => Color::Indigo,
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Learning Materials')
                    ->icon('heroicon-o-academic-cap')
                    ->collapsed(),
                
                NavigationGroup::make()
                    ->label('Reservation')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->collapsed(),

                NavigationGroup::make()
                    ->label('Inventory')
                    ->icon('heroicon-o-archive-box'),
            ])

            ->resourceCreatePageRedirect('index')
            ->resourceEditPageRedirect('index')

            ->discoverResources(in: app_path('Filament/Portal/Resources'), for: 'App\\Filament\\Portal\\Resources')
            ->discoverPages(in: app_path('Filament/Portal/Pages'), for: 'App\\Filament\\Portal\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Portal/Widgets'), for: 'App\\Filament\\Portal\\Widgets')
            ->widgets([
                //AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make()
                    ->navigationLabel('Roles And Permission')
                    ->navigationGroup('Superadmin Settings')
                    ->navigationSort(2),

                FilamentSpatieLaravelHealthPlugin::make()
                    ->authorize(fn (): bool => auth()->user()->email === 'superadmin@gmail.com')
                    ->navigationLabel('Website Health Check')
                    ->navigationGroup('Superadmin Settings')
                    ->navigationSort(3),

                SlickScrollbarPlugin::make()
                    ->size('6px')
                    ->palette('primary')
                    ->hoverColor(Color::Amber, 700),
            ]);
    }
}
