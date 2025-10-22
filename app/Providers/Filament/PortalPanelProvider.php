<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Actions\Action;
use Filament\Enums\ThemeMode;
use Filament\Pages\Dashboard;
use App\Filament\Pages\Backups;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Enums\UserMenuPosition;
use Filament\Navigation\NavigationGroup;
use Filament\Widgets\FilamentInfoWidget;
use App\Filament\Pages\HealthCheckResults;
use Filament\Http\Middleware\Authenticate;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Asmit\ResizedColumn\ResizedColumnPlugin;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;
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
            ->userMenu(position: UserMenuPosition::Sidebar)
            ->globalSearch(true)
            ->breadcrumbs(true)
            
            ->login()
            ->registration()
            ->passwordReset()
            ->emailVerification()
            ->emailChangeVerification()
            ->authGuard('web')

            ->profile()

            ->font('Poppins')
            //->defaultThemeMode(ThemeMode::Light)
            ->brandLogoHeight('2rem')
            ->brandLogo(asset('assets/logo/light-theme-alt.png'))
            ->darkModeBrandLogo(asset('assets/logo/dark-theme.png'))
            ->favicon(asset('assets/favicon/favicon.ico'))

            ->colors([
                //Nord Theme Colors
                'danger' => Color::hex('#bf616a'), // nord11
                'gray' => [
                    50 => '#eceff4',  // nord6 - snow storm
                    100 => '#e5e9f0', // nord5 - snow storm
                    200 => '#d8dee9', // nord4 - snow storm
                    300 => '#a7b1c5',
                    400 => '#8c9ab3',
                    500 => '#71829b',
                    600 => '#4c566a', // nord3 - polar night
                    700 => '#434c5e', // nord2 - polar night
                    800 => '#3b4252', // nord1 - polar night
                    900 => '#2e3440', // nord0 - polar night
                    950 => '#232831',
                ],
                'info' => Color::hex('#81a1c1'), // nord9
                'primary' => Color::hex('#013267'), // pitbi orange
                'secondary' => Color::hex('#fe800d'), // pitbi blue
                'success' => Color::hex('#a3be8c'), // nord14
                'warning' => Color::hex('#ebcb8b'), // nord13
                'polarnight' => Color::hex('#3b4353'), // nord1

                //Neutral Colors
                'gray' => Color::Gray,
                'zinc' => Color::Zinc,
                'neutral' => Color::Neutral,
                'slate' => Color::Slate,
                'stone' => Color::Stone,

                //Tailwind Colors
                'red' => Color::Red,
                'rose' => Color::Rose,
                'orange' => Color::Orange,
                'amber' => Color::Amber,
                'yellow' => Color::Yellow,
                'green' => Color::Green,
                'emerald' => Color::Emerald,
                'teal' => Color::Teal,
                'cyan' => Color::Cyan,
                'sky' => Color::Sky,
                'blue' => Color::Blue,
                'indigo' => Color::Indigo,
                'violet' => Color::Violet,
                'purple' => Color::Purple,
                'fuchsia' => Color::Fuchsia,
                'pink' => Color::Pink,
            ])

            ->viteTheme('resources/css/filament/portal/theme.css')
            
            ->userMenuItems([
                'logout' => fn (Action $action) => $action->label('Log out'),
            ])

            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Learning Materials')
                    ->icon('heroicon-o-academic-cap'),
                
                NavigationGroup::make()
                    ->label('Reservation')
                    ->icon('heroicon-o-clipboard-document-check'),

                NavigationGroup::make()
                    ->label('Inventory')
                    ->icon('heroicon-o-archive-box'),
                
                NavigationGroup::make()
                    ->label('System Settings')
                    ->icon('heroicon-o-cog-6-tooth'),
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
                    ->navigationGroup('System Settings'),

                FilamentSpatieLaravelHealthPlugin::make()
                    ->authorize(fn (): bool => auth()->user()->email === 'superadmin@gmail.com')
                    ->navigationLabel('Website Health Check')
                    ->navigationGroup('System Settings'),

                ResizedColumnPlugin::make()
                    ->preserveOnDB(false),

                FilamentApexChartsPlugin::make(),

                BreezyCore::make()
                    ->avatarUploadComponent(fn($fileUpload) => $fileUpload->disableLabel())
                    ->enableBrowserSessions(condition: true)
                    ->myProfile(
                        shouldRegisterUserMenu: true, // Sets the 'account' link in the panel User Menu (default = true)
                        userMenuLabel: 'My Profile', // Customizes the 'account' link label in the panel User Menu (default = null)
                        shouldRegisterNavigation: false, // Adds a main navigation item for the My Profile page (default = false)
                        navigationGroup: 'Settings', // Sets the navigation group for the My Profile page (default = null)
                        hasAvatars: true, // Enables the avatar upload form component (default = false)
                        slug: 'my-profile', // Sets the slug for the profile page (default = 'my-profile')
                        //force: false, // force the user to enable 2FA before they can use the application (default = false)
                    ),
            ]);
    }
}
